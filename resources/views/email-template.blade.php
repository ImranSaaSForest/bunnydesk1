<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
</head>

<style>
    .input-box {
        display: flex;
        width: 100%;
        justify-content: flex-end;
        margin-top: 50px;

    }
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container {
        width: 80%;
        /* or a specific width like 960px */
        margin-right: auto;
        /* centers the container */
        margin-left: auto;
        /* centers the container */
        padding: 20px;
        /* optional: space inside the container */
    }

    .company-name {
        background-color: yellow;
        padding: 40px;
    }

    .company-name h1 {
        text-align: center;
        font-size: large;
    }

    .condidate {
        margin-top: 20px;
    }

    .address {
        margin-top: 3px;
    }
    .condidate h1 {
        font-size: 20px;
        margin-top: 4px;
    }
   p{
    word-break: break-all;
   }
</style>

<body>

    <div class="container">
        <!-- logo -->
        <div class="company-name">
            <h1>Gurus Human Resource Consultancy Group</h1>
        </div>
        <!-- condidate details -->
        <div class="condidate">
            <h1>Name:{{ $firstname }}{{ $lastname }}</h1>
            <h1>Job Role:{{$job}}</h1>
            <div class="address">
                <i><h4>SaaS Forest Madurai-625007</h4>
                <a style="font-size: 18px;" href="https://www.saasforest.com/">www.saasforest.com</a>
                <h4>Contact:4044 67880 770</h4></i>
            </div>

            <div style="margin-top: 30px;" class="">
                <p style="">{!! $msg !!}</p>

            </div>
        </div>


    </div>


</body>

</html>
