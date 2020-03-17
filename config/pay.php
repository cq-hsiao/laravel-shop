<?php

return [
    'alipay' => [
        'app_id'         => '2016101900721855',
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA9Zc+bp55w/nP0GQ+ezyfxqRzjhRtEy9+JbwvzsP59Cwj4lpJlB9laSF9gOI+m/E8G4c8BrZelT7UJNizWrlW4jmTkGvFY5qAEXdNy79Q7VEZf2rQpFFtxuwcfLW+6xR1v8Xd8TwzZ0ang1C+yP4Kc+NAq8R76fCpbgiCh7CKtY8K0E3Hq3ts2BfLw4khGRSfqa+sw7K1MusQTIRuT8xNv1oPVDHLYV/7WUw+/BQnyoV/UL6SlSbqgLxntHZBMH7P65uxCG+b7fAhsXV+T2Z0cka2Aa6+ceEqJAvroA/cqcqp9ay2YAa7H/vsRhpUnAqXf1G826xeandstyLeO6HOeQIDAQAB',
        'private_key'    => 'MIIEowIBAAKCAQEAqn+WWwY8QBGXl3+61717u0LNr9huebx88zA465Ky/GEmG5OSaCPtn9VFoxSN7hvP1gbYMU6ZTPXYf1xAYPH871j2OEIbidkxdk6CI7HvbNbRvcCiDKyMKqJqhTzhZHmPDCG3sq73owjRX+KR5c5+KDR7b2WScyLBd13Fj0S2mbWvzCWIQQden3o35jk40y8i219+J4ri7xdHFp+RoHIEHGFyOpJY6No2csrvcIo+1WbWRFfY4F54NNix3EsMBRMq0zocZ/pIWwig0m6DqnoUuSKWyEXVlSyON35J3ToSzJDV5jUL3Ebi+Jiu11H2CtI/xza07Cxk1cwThQ+UgG2XWQIDAQABAoIBAG6vmbrKF384bINr5q58UsFFIycPiNj7Jtrx6WH1wMDbiNPKP/ffqzpiSG0QwPtdjtXRZ7TK4/b0e3JjP2AweRYhWOc484vQNq4pVY86ysaLx2o6jnlP40ciXajV1zVVPSqRESKtOBLdfJ+VBUZ9gaU0PRYaiLK6U4Lcm47g+vJZ0E5YnyTMm244A3rv2XW0JFGw0Y+VBrtvjxwqKKHjkZey3WFLE1tpnXuHPLE0j4jUzBxbgDLKqUsKqt+uxN63dNt/W/FZjkP4LS0SZOcG8Yr+XjmQyhJ2NeoDYHL7+IEHTWKTs3MtR4tUOgf9TmWH1Y02VZ3U9zVZcqUCgEuGsyECgYEA9Z93tJpfLuplHmNl6MD6LAmy85R6lEx1pX9woXwWMVkteHyST/dwbgzFwLFQ8zWMz57S92/0HWxZ7usfKWOgzN6PVyqo2SLgpjYP5Z15gEIr7iy24Z6bIzciAIkEUomHDeA9FJvbzRIL6FKqyLGUDEoqS8ZWWu871i/YUToGzFUCgYEAsbOcbbnptdVu6VVExGs5m/Bj2SRrVKlbTRCu8HRiQKlmOSLzbm9kRA2pe2CFPNzBAGTZurASAA91yNxXuNDDOeuLQbceGX3kBySMn6eg5Ncj2eMtytjtL+/gzWyi82/FFq2xRAYDQ3taI91odBRiAgW1KLRdGjeGQ25uq/Jl4vUCgYAanBgf3MmLD6G81Q/pU17G/pYAhYlyH4ZvU4skM4lD92FRuE0xlKD9iyyxX4RhlN+YbqB0ZhCQT3i+xYIuvbhh+YqV6u03+OtVlm3KdnD/UCvqNbXqY4BzJDnuzOlG99dFzZFQdkItyVK47JvL+lELs805QFeCqoBVCAHratnpsQKBgBz3dUlho9ozJ3g7oREPlX146x3LVP+g97QRQyMJJbb6piIsM1hOKh75xyXIbw+jwIZK6j8HUnfWDVInsNj8lsZLQhD9Q5fOMKyFZbLkxVJoS3zKDn3hbJCC3rc9vTZHgu4WdC3tePy4D9KG1e8OH4fK3GP9Oqv6XxWA2+OFiNw9AoGBAKqzMol/K5h4YvU2D3bG855eg2MyPw+KmyaY/sg1CMzpjam7jRb7Q3RnCkdRAYtrtQTotZtjj2kctj8930XOrNqUY+W94nCaWQRuTjurjqM28E2MgJuOgdaoYMsBu3+r87pkpSzuX9AYtaOVspyz32MvSj4FScZf6S28mE5y0MSD',
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