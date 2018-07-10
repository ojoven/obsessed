@extends('layouts.in')
@section('class', 'timeline')

@section('content')

    <!-- TIMELINE -->
    <section class="timeline-wrapper container">

        <ul class="timeline">

            <!-- POST -->
            <li class="post">

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

                        <div class="body">
                            <p><?php echo strip_tags('<p>Hello,</p> <p>How about a service, which enables you to show your users plotted across the globe?</p> <p>Sounds interesting?</p> <p>Searched across. Found only WordPress plugins.</p> <p>Do share if you know of any service, other than WP plugins that does this.</p> <p>Keep shining.</p>'); ?></p>
                        </div>
                    </a>

                </div>

            </li>

            <li class="comment">

            </li>

        </ul>

    </section>

@stop