@extends('layouts.in')
@section('class', 'timeline')

@section('content')

    <!-- TIMELINE -->
    <section class="timeline-wrapper container">

        <header class="statistics">

        </header>

        <ul class="timeline">

            <!-- POST -->
            <li class="post content">

                <div class="content-wrapper">

                    <a href="#" class="content-link">

                        <div class="header">

                            <span class="author">ojoven</span>
                            <span class="source-wrapper">
                                posted on <span class="source">Reddit</span>
                            </span>
                            <span class="date">5 minutes ago</span>

                        </div>

                        <h2 class="title">Ask IH :- Is mapping users on your website a good idea?</h2>

                        <div class="thumbnail" style="background-image:url(https://i.redditmedia.com/J_LDCDdto6vqmtKB7ot0w_354XWZa_rgrnVqxG6SHi4.jpg?s=8e675abfce40418fefd121854b6da9b6);"></div>

                        <div class="body">
                            <p><?php echo strip_tags('<p>Hello,</p> <p>How about a service, which enables you to show your users plotted across the globe?</p> <p>Sounds interesting?</p> <p>Searched across. Found only WordPress plugins.</p> <p>Do share if you know of any service, other than WP plugins that does this.</p> <p>Keep shining.</p>'); ?></p>
                        </div>
                    </a>

                </div>

            </li>

            <!-- COMMENT -->
            <li class="comment content">

                <div class="content-wrapper">

                    <a href="#" class="content-link">

                        <div class="header">

                            <span class="author">ojoven</span>
                            <span class="source-wrapper">
                                posted a reply on <span class="source">Reddit</span>
                            </span>
                            <span class="date">5 minutes ago</span>

                        </div>

                        <h2 class="to-post">Ask IH :- Is mapping users on your website a good idea?</h2>

                        <div class="body">
                            <p><?php echo strip_tags('The computer learning model is also fed a ton of other non-acoustical data. Things like population density (from census data), the proximity to roads, airports or industry, average rainfall and temperature, levels of light pollution, etc. Once it is trained on that data it then looks at your area and draws correlations. Your house is not near an airport or national park, but it is in a place that has aspects that are similar, and the model draws those correlations to make its prediction.'); ?></p>
                        </div>
                    </a>

                </div>

            </li>

        </ul>

    </section>

@stop