<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>certificate</title>
    <style>
        * {
            box-sizing: border-box;

        }

        @font-face {
            font-family: 'Elegance';
            font-weight: normal;
            font-style: normal;
            font-variant: normal;
            src: url("http://eclecticgeek.com/dompdf/fonts/Elegance.ttf") format("truetype");
        }

        html,
        body,
        * {
            height: fit-content;
            text-align: center;
            margin: 0px;
            padding: 0px;
            font-family: Elegance, sans-serif;

        }

        @page {
            margin: 0px;
        }

        .cert-bg {
            position: absolute;
            left: 0px;
            top: 0;
            z-index: -1;
            width: 100%;
        }

        .employeeName {
            border-bottom: 2px solid #022449;

            width: fit-content;
            height: fit-content;
            padding: 0px 10px 0px 15px;
        }

        /* .bottomText{
    border: 2px solid green;

    position: absolute;
    bottom: 0;
    right: 0
} */
    </style>
</head>

<body>
    <div style="width: 1100px;height:100% !important">
        <img src="data:image/svg+xml;base64,<?php echo base64_encode(file_get_contents(base_path('public/images/certificate-bg.png'))); ?>"
            class="cert-bg" alt="" />
        <div style="margin-left:340px;width:fit-content:height:100vh">
            <img width="300px" style="z-index: 10;margin-top: 80px;margin-left: 30px"
                src="data:image/svg+xml;base64,<?php echo base64_encode(file_get_contents(base_path('public/images/certificate-text.png'))); ?>"
                alt="" />
            <div style="font-size:1.5rem;margin-top: 35px ">THIS CERTIFICATE IS PROUDLY PRESENTED TO:</div>
            <div>
                <p style="font-size:2.8rem;color:#022449;
margin-top: 50px;
                ">
                    <b class="employeeName">
                        {{-- {{$instructor?$employeeName:''}} --}}
                        {{substr($employeeName?$employeeName:'',0,25)}}

                    </b>
                </p>

            </div>
            <div style="font-size: 20px;width:70%;text-align:center;margin:0 auto;line-height:1.3;padding-top: 40px">For successfully
                completing the course <b>

                    {{substr($courseTitle?$courseTitle:'',0,80)}}{{strlen($courseTitle?$courseTitle:'')>80?'...':''}}
                </b> on {{$completionDate?$completionDate:''}}
                with the score of <b>{{!is_null($score)?$score:''}}%</b>.</div>

            <div style="width:100%;" class="bottomText">

                <div style="position:absolute;bottom:0;right:50%;margin-bottom:4% ">

                    <p style="font-size:25px;color:#022449;text-decoration:underline;

                   ">
                        {{substr($instructor?$instructor:'',0,20)}}
                    </p>
                    <p style="font-size: 15px;

">Instructor</p>
                </div>

                <img width="190px" style="margin-left: 60%;margin-bottom:4% ;position:absolute;bottom:0;right:8%"
                    src="data:image/svg+xml;base64,<?php echo base64_encode(file_get_contents(base_path('public/images/guru hr logo 1.png'))); ?>"
                    class="" alt="" />

            </div>
        </div>
    </div>

</body>

</html>
