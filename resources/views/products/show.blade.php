@extends('layouts.app')
@section('title', $product->title)

@section('content')
    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card">
                <div class="card-body product-info">
                    <div class="row">
                        <div class="col-5">
                            <img class="cover" src="{{ $product->image_url }}" alt="">
                        </div>
                        <div class="col-7">
                            <div class="title">{{ $product->title }}</div>
                            <div class="price"><label>价格</label><em>￥</em><span>{{ $product->price }}</span></div>
                            <div class="sales_and_reviews">
                                <div class="sold_count">累计销量 <span class="count">{{ $product->sold_count }}</span></div>
                                <div class="review_count">累计评价 <span class="count">{{ $product->review_count }}</span></div>
                                <div class="rating" title="评分 {{ $product->rating }}">评分 <span class="count">{{ str_repeat('★', floor($product->rating)) }}{{ str_repeat('☆', 5 - floor($product->rating)) }}</span></div>
                            </div>
                            <div class="skus">
                                <label>选择</label>
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    @foreach($product->skus as $sku)
                                        <label
                                                class="btn sku-btn"
                                                data-price="{{ $sku->price }}"
                                                data-stock="{{ $sku->stock }}"
                                                data-toggle="tooltip"
                                                title="{{ $sku->description }}"
                                                data-placement="bottom">
                                            <input type="radio" name="skus" autocomplete="off" value="{{ $sku->id }}"> {{ $sku->title }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="cart_amount"><label>数量</label><input type="text" class="form-control form-control-sm" value="1"><span>件</span><span class="stock"></span></div>
                            <div class="buttons">
                                @if($favored)
                                    <button class="btn btn-danger btn-disfavor">取消收藏</button>
                                @else
                                    <button class="btn btn-success btn-favor">❤ 收藏</button>
                                @endif
                                 <button class="btn btn-primary btn-add-to-cart">加入购物车</button>
                            </div>
                        </div>
                    </div>
                    <div class="product-detail">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" href="#product-detail-tab" aria-controls="product-detail-tab" role="tab" data-toggle="tab" aria-selected="true">商品详情</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#product-reviews-tab" aria-controls="product-reviews-tab" role="tab" data-toggle="tab" aria-selected="false">用户评价</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="product-detail-tab">
                                {!! $product->description !!}
                            </div>
                            <div role="tabpanel" class="tab-pane" id="product-reviews-tab">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('cssForPage')
    <style type="text/css">
        .swal-text {
            background-color: #FEFAE3;
            padding: 17px;
            border: 1px solid #F0E1A1;
            display: block;
            margin: 22px;
            text-align: center;
            color: #61534e;
        }
    </style>
@endsection
@section('scriptsAfterJs')
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
            $('.sku-btn').click(function () {
                $('.product-info .price span').text($(this).data('price'));
                $('.product-info .stock').text('库存：' + $(this).data('stock') + '件');
            });

            $('.btn-favor').click(function () {
                // 发起一个 post ajax 请求，请求 url 通过后端的 route() 函数生成。
               axios.post('{{route('products.favor',['product'=>$product->id])}}')
                   .then(function (data) {// 请求成功会执行这个回调
                       if(data.data.status == 1){
                           swal('宝贝收藏成功！','','success');
                       } else {
                           swal('宝贝已收藏~','','warning');
                       }
                   }, function(error) { // 请求失败会执行这个回调
                       // 如果返回码是 401 代表没登录
                       if (error.response && error.response.status === 401) {
                           swal({
                               buttons: {
                                   confirm: {
                                       text: "登录",
                                       value: true,
                                       visible: true,
                                       className: "confirm-btn",
                                       closeModal: true,
                                   },
                                   cancel: {
                                       text: "取消",
                                       value: null,
                                       visible: true,
                                       className: "",
                                       closeModal: true,
                                   },
                               },
                               title: "请先登录再收藏~",
                               icon: "warning",
                           }).then(function(isConfirm){
                               if(isConfirm){
                                   location.href = '{{route('login')}}';
                               }
                           })

                       } else if (error.response && (error.response.data.msg || error.response.data.message)) {
                           // 其他有 msg 或者 message 字段的情况，将 msg 提示给用户
                           swal(error.response.data.msg ? error.response.data.msg : error.response.data.message, '', 'error');
                       } else {
                           // 其他情况应该是系统挂了
                           swal('系统错误', '', 'error');
                       }
                   });
            });

            $('.btn-disfavor').click(function () {
                var _this = this;
                axios.delete('{{ route('products.disfavor', ['product' => $product->id]) }}')
                    .then(function () {
                        swal('已取消收藏~', '', 'success')
                            .then(function () {
                                location.reload();
                            });
                    });
            });

            // 加入购物车按钮点击事件
            $('.btn-add-to-cart').click(function () {

                // 请求加入购物车接口
                axios.post('{{route('cart.add')}}',{
                    sku_id : $('label.active input[name=skus]').val(),
                    amount: $('.cart_amount input').val(),
                }).then(function () {
                    // 请求成功执行此回调
                    // swal('加入购物车成功', '', 'success');
                    swal({
                        title: "加入购物车成功!",
                        text: "是否前往购物车进行结算？",
                        icon: "success",
                        buttons: ['取消', '确定'],
                        dangerMode: true,
                    }).then(function(isConfirm) {
                        if (!isConfirm) {
                            return;
                        }
                        location.href = '{{ route('cart.index') }}';
                    });
                }, function (error) {
                    // 请求失败执行此回调
                    if (error.response.status === 401) {

                        // http 状态码为 401 代表用户未登陆
                        swal('请先登录', '', 'error').then(function(){
                            location.href = '{{ route('login') }}';
                        });

                    } else if (error.response.status === 422) {

                        // http 状态码为 422 代表用户输入校验失败
                        var html = '<div>';
                        _.each(error.response.data.errors, function (errors) {
                            _.each(errors, function (error) {
                                html += error + '<br>';
                            })
                        });
                        html += '</div>';
                        swal({content: $(html)[0], icon: 'error'})
                    }else {

                        // 其他情况应该是系统挂了
                        swal('系统错误', '', 'error');
                    }
                })
            });

            });
    </script>
@endsection