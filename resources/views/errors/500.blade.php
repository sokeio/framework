<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>500 Not Found</title>
    <style>
        .page_500 {
            padding: 40px 0;
            background: #fff;
            font-family: 'Arvo', serif;
        }

        .page_box {
            max-width: 700px;
            margin: 0 auto;
        }

        .page_500 img {
            width: 100%;
        }

        .four_zero_four_bg {

            background-image: url({{ url('platform/modules/sokeio/er404.gif') }});
            height: 400px;
            background-position: center;
        }


        .four_zero_four_bg h1 {
            font-size: 80px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_500 {
            color: #fff !important;
            padding: 10px 20px;
            background: #39ac31;
            margin: 20px 0;
            display: inline-block;
            text-decoration: none;
        }

        .contant_box_500 {
            margin-top: -50px;
        }

        .text-center {
            text-align: center
        }
    </style>
</head>

<body>
    <section class="page_500">
        <div class="text-center page_box">
            <div class="four_zero_four_bg">
                <h1 class="text-center ">500</h1>
            </div>

            <div class="contant_box_500">
                <h3 class="h2">
                    Look like you're lost
                </h3>

                <p>the page you are looking for not avaible!</p>

                <a href="{{ url('/') }}" class="link_500">Go to Home</a>
            </div>
        </div>
    </section>
</body>
