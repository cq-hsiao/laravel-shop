<?php

return [
    'alipay' => [
        'app_id'         => '2016101900721855',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA9Zc+bp55w/nP0GQ+ezyfxqRzjhRtEy9+JbwvzsP59Cwj4lpJlB9laSF9gOI+m/E8G4c8BrZelT7UJNizWrlW4jmTkGvFY5qAEXdNy79Q7VEZf2rQpFFtxuwcfLW+6xR1v8Xd8TwzZ0ang1C+yP4Kc+NAq8R76fCpbgiCh7CKtY8K0E3Hq3ts2BfLw4khGRSfqa+sw7K1MusQTIRuT8xNv1oPVDHLYV/7WUw+/BQnyoV/UL6SlSbqgLxntHZBMH7P65uxCG+b7fAhsXV+T2Z0cka2Aa6+ceEqJAvroA/cqcqp9ay2YAa7H/vsRhpUnAqXf1G826xeandstyLeO6HOeQIDAQAB',
        'private_key'    => 'MIIEpAIBAAKCAQEAxJwJSVF7AZxkr4j4t5o02ZTwwN2DFyQ0++625hSB44gajz3wHjPzzJlPr2GJBVgefKRqJ7Yf2vC8REgWl0f7CwLiy80xZ9L320h4YG7EimQkru0Vd8w0Zq6OIsB7HYdBLPHB6d0YVhHGf9hTKTiG8jxfW3oJa5gfbPl/Ofsuw3TLfqAPk3fDebvD+Cio4QlQ3RpHHrgte6/Kueb5rfDXi9I7/wezzl6ukn5cmJr8m+n/gsSmOviEgAcWu/I7W5BefDuxs0NorVarkkjm8Ngro9Mbfs2nQujkkD0HZqwMP+Jdw+0ItQGQ0VhC4vLwUqYNIWDukJJ/kpsYODb3/PC++QIDAQABAoIBAD+y0itn87Nc6R4aAYLyuia4Y5wI6Hzp5SSufaFjiYWfRgOcrJwMdvOVxERtFEif+Rim6CG/luiHUGfK907CKmqx36rp6xTZPCZWf3G20YSI1325IFh015FhAnnt5LV3ab1XAl1znXvdPHOVMbRMNSRsPPTVQBIU8jGLUBYH9Gmsbi5PJsBUB0LNQYEuIn366HHO6mmJk6PukxASMgPo7xfwRXRgUZYMyokb4BEf6Q1WF75QUKBsGkz3ul/mZFbiGbVRCNhpvq0sKi9KMcLDuqXFJ96abDE0UcXjTn5WbblHQhsKybB1Atgm4U6a8TnmbrwgYEQK1v4NYszYSOY1oFECgYEA+tSyypD3mCVEcQu4g9m4zyxQk2Zocgez6g9tBAKeSeXNiwuQZizcrn8dJlkZvMSM4sxYYWBD0F5nLlv8Gmv8DHg1l54J15MJhkguzBWOqrccNj8nrcvtYboefREeIaSyRtdh/XOikxS+4AolkmX3GsWjPn4QUTj3COY3T7Hngz0CgYEAyKlIqJ3RWhysav7ddGV/IwZWgnZ+eVP1bWVjbLQsNoQooKR/UkfD5SqVfNcO1EGeIgx+un6n/LY8OAB2jcS2ZcWJe1nbSETpYlnnW42o//K1gFMqJHzS5G7UHBeMNCGrJc92sgt7MCr+YS1dbCQsJd6zFdBT96BGynxdadVbNm0CgYEA2LHsjEfVPyHjEO6ZR39Ow9x69/ye+vRgoDMXcUF50kmv2xHwe/UB6dZzLnAHZic/t6fmKqnprBgCd/CLyZ/Erlkjo4qq4gxrTBEMLM/q4t0yiELjWqg0uhr6v/2L1HDee4kTZM0DhKGFjTP25ZUld4GK2DA8lI7sDEonOrhtU9kCgYEAg6qotZOQuntUG4UO1QCAcwAGpeaQyJXx1g2QNtTmqgMAEaC+tsPGY8oUeu02mspPs/HU+hR/sA/35ZtQL2gjcxYyRxFIFNy680Eg0W9btE6TEBgB8B1D2IZsgd5lrhNllRLTxLJJ6+paLnOw+HOn0FWX1zlyz2qI6Pa/6OEJz+UCgYAu8/QpZdZ03Eo6tdBRIP4gEjUOnX9r9fZ+d8vmsIfUDuuPphNLRLKHAnVOUrjuPEHGVyt0J9cyV1mI2mr5VgM19bW9hRbbZfqjUpmCW/iGWFe0FuMglBc01I5nkum5bEwHHyF3HQq1wcaQqyRSv9hfC26Iy37OigL/smJtQOj4DQ==',
        'log'            => [
            'file' => storage_path('logs/alipay.log'),
        ],
    ],

    'wechat' => [
        'app_id'      => '',
        'mch_id'      => '',
        'key'         => '',
        'cert_client' => '',
        'cert_key'    => '',
        'log'         => [
            'file' => storage_path('logs/wechat_pay.log'),
        ],
    ],
];