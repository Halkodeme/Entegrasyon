<style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }

        
        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('mainSlider1.jpg');
            background-size: cover;
            background-position: center;
            z-index: 0;
        }

        .container {
            flex: 1;
            z-index: 1; 
        }

        .navbar-custom {
            background-color: #1b0565;
            height: 70px;
        }

        .navbar-custom .nav-link {
            color: white !important;
        }

        .navbar-brand {
            flex-grow: 1;
            text-align: center;
        }

        .navbar-nav {
            flex-direction: row;
            justify-content: center;
            width: 100%;
        }

        footer {
            background-color: #1b0565;
            color: white;
            z-index: 1; 
            margin-top: auto; 
        }

        .btn-custom {
            background-color: #1b0565;
            color: white;
        }

        .btn {
            background-color: #1b0565;
            color: white;
            margin-bottom: 10px;
        }

        td {
            word-wrap: break-word;
            word-break: break-word;
            max-width: 250px;
        }

        .content {
      position: relative;
      z-index: 0;
      color: white;
      text-align: center;
      padding-top: 2%;
  }
        
    </style>