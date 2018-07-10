<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timeline - Side Projects | Obsessed Club</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:300,400,700" rel="stylesheet">

    <link rel="stylesheet" href="/css/app/style.css">

</head>


<body class="@yield('class')">

<main id="main-container">

    @yield('content')

</main>

<footer>

    <ul class="menu-footer">

        <li>
            <a class="menu-timeline" href="/timeline">
                <i class="fa fa-th-list"></i>
                <span class="srt">Timeline</span>
            </a>
        </li>

        <li>
            <a class="menu-timeline" href="/statistics">
                <i class="fa fa-bar-chart"></i>
                <span class="srt">Statistics</span>
            </a>
        </li>

        <li>
            <a class="menu-notifications" href="/notifications">
                <i class="fa fa-bell"></i>
                <span class="srt">Notifications</span>
            </a>
        </li>

        <li>
            <a class="menu-profile" href="/profile">
                <i class="fa fa-user"></i>
                <span class="srt">Profile</span>
            </a>
        </li>

    </ul>

</footer>

<script>
    var urlBase = "<?php echo url('/'); ?>";
</script>

<script type="text/javascript" src="/js/app.min.js"></script>

</body>
</html>