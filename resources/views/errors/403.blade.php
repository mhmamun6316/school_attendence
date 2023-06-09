<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403</title>

    <style>
        body {
            min-height: 100vh;
            background: #F3F8FB;
        }
        .container{
            min-height: 100vh;
            display: flex;
        }
        .error-content {
            background: #fff;
            width: 100%;
            max-width: 600px;
            margin: auto;
            padding: 70px 30px;
        }
        .error-content h2 {
            font-size: 98px;
            font-weight: 800;
            color: #686cdc;
            margin: 20px 0px;
            text-shadow: -3px -3px 0 #ffffff, 3px -3px 0 #ffffff, -3px 3px 0 #ffffff, 3px 3px 0 #ffffff, 4px 4px 0 #6569dc, 5px 5px 0 #6569dc, 6px 6px 0 #6569dc, 7px 7px 0 #6569dc;
            font-family: 'lato', sans-serif;
        }
        .error-content p {
            font-size: 17px;
            color: #787bd8;
            font-weight: 600;
        }

        .error-content a {
            text-decoration: none;
            display: inline-block;
            margin-top: 40px;
            background: #656aea;
            color: #fff;
            padding: 16px 26px;
            border-radius: 3px;
        }
        .error-area{
            text-align: center;
        }
    </style>
</head>
<body>
<div class="error-area">
    <div class="container">
        <div class="error-content">
            <h2>403</h2>
            <p>Sorry ! You are not authorized for that!!</p>
            <a href="{{ route('dashboard') }}">Back to Dashboard</a>
            <a href="{{ route('admin.login') }}">Login Again !</a>
        </div>
    </div>
</div>
</body>
</html>
