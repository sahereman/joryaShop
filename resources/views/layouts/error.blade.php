<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 36px;
            padding: 20px;
        }

        .title a {
            color: #636b6f;
            text-decoration: none;
        }
        .error_box {
		    width: 100%;
		}
		.error_box img {
			width: 10rem;
		}
		.content {
	        font-size: 2rem;
	        color: #d9d9d9;
	        margin: .5rem;
	    }
	    .btnBox {
	    	width: 100%;
	    }
	    .btnBox a {
	    	font-size: 1rem;
		    border: 1px solid;
		    padding: .6rem;
		    border-radius: 50%/100%;
	    }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title">
            @yield('message')
        </div>
    </div>
</div>
</body>
</html>
