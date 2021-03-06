@extends('layouts.in')
@section('class', 'page-notifications')

@section('content')

    <!-- NOTIFICATIONS -->
    <section class="notifications-wrapper container">

        <h1>Notifications</h1>

        <ul class="notifications">

            <li class="notification">

                <span class="title">
                    /r/SideProject on Reddit
                </span>

                <ul class="options">

                    <li class="timeline">
                        <i class="fa fa-th-list"></i>
                        <input id="notification-timeline" type="checkbox" value=""/>
                        <label for="notification-timeline" class="toggle"><span></span></label>
                    </li>

                    <li class="push">
                        <i class="fa fa-mobile-phone"></i>
                        <input id="notification-push" type="checkbox" value=""/>
                        <label for="notification-push" class="toggle"><span></span></label>
                    </li>

                    <li class="email">
                        <i class="fa fa-envelope-o"></i>
                        <input id="notification-email" type="checkbox" value=""/>
                        <label for="notification-email" class="toggle"><span></span></label>
                    </li>

                </ul>

            </li>

        </ul>

    </section>

@stop