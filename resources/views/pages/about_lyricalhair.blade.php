@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? 'About lyrical' : 'About lyrical') . ' - ' . \App\Models\Config::config('title'))
@section('content')
  {{-- 关于我们文本内容 --}}
  <div class="about-lyrical-content">
    <div class="article-banner">
      <img src="{{ asset('img/articles/articles-bg.png') }}" alt="Lyricalhair">
    </div>
    <div class="article-content">
      {{-- 分类 --}}
      <div class="navigation-box">
          <div class="article-navigation">
            <div class="navigation-type active">
              <a href="{{ route('about_lyricalhair') }}">
                <img class="normal-logo" src="{{ asset('img/articles/about-logo.png') }}" alt="Lyricalhair">
                <img class="active-logo" src="{{ asset('img/articles/about-logo-active.png') }}" alt="Lyricalhair">
                <p class="link-button">About Us</p>
              </a>
            </div>
            <div class="navigation-type">
              <a href="{{ route('seo_url', ['slug' => 'contact_us']) }}">
                <img class="normal-logo" src="{{ asset('img/articles/contact-logo.png') }}" alt="Lyricalhair">
                <img class="active-logo" src="{{ asset('img/articles/contact-logo-active.png') }}" alt="Lyricalhair">
                <p class="link-button">Contact Us</p>
              </a>
            </div>
            <div class="navigation-type">
              <a href="{{ route('why_lyricalhair') }}">
                <img class="normal-logo" src="{{ asset('img/articles/why-logo.png') }}" alt="Lyricalhair">
                <img class="active-logo" src="{{ asset('img/articles/why-logo-active.png') }}" alt="Lyricalhair">
                <p class="link-button">Why Lyricalhair</p>
              </a>
            </div>
          </div>
      </div>
      {{-- 文章内容 --}}
      <div class="article-nvarchar main-content">
        {{-- 文章大标题 --}}
        <div class="nvarchar-title">
          <span>About Us</span>
        </div>
        {{-- 具体文本 --}}
        <div class="nvarchar-modules">
           {{-- 模块一 --}}
          <div class="about-us-part1">
            <div class="aboutus-part1-left">
              <img src="{{ asset('img/articles/about-1.png') }}" alt="Lyricalhair">
            </div>
            <div class="aboutus-part1-right">
              <p><span>About Us</span></p>
              <p>We would like to express our deep appreciation for our loyal customers and to our new visitors.</p>
              <p>It is our hope that this outline of our history, services and mission, will demonstrate to you the great responsibility we feel towards all those who try our products.</p>
              <p>We are compassionate and diligent about solving your hair-loss problems. Per our reputation our services have proved invaluable to those who take advantage of this special opportunity.</p>
            </div>
          </div>
          {{-- 模块二 --}}
          <div class="about-us-part2">
            <p>LyricalHair has been providing dependable hair replacement system manufacturing services to our customers since 1999. During the past 20 years we have been operating from three branches located in China, Dubai, and The United States. Our brand is currently able to ship to over 30 countries. As members of the Wig Association and being highly rated by the Better Business Bureau (BBB) our customers are sure to receive the highest quality product available. Our charitable works include generous donations to such deserving causes as: Cancer Battles Supporters, Hello Generous!, Everybody Gets to Eat Inc. and many more. Our company is currently exporting stock at a rate of 6,000 orders per month and looking forward to reaching our goal of 10,000 per month soon.</p>
            <div class="aboutus-part2-circles">
              <div class="part2-circle">
                <span class="describe">since</span>
                <span class="num">1999</span>
              </div>
              <div class="part2-circle">
                <span class="describe">countries</span>
                <span class="num">30</span>
              </div>
              <div class="part2-circle">
                <span class="describe">orders now</span>
                <span class="num">6000</span>
              </div>
              <div class="part2-circle">
                <span class="describe">orders goal</span>
                <span class="num">10000</span>
              </div>
            </div>
          </div>
          {{-- 模块三 --}}
          <div class="our-services">
            <p class="services-title"><span>Our services</span></p>
            <div class="services-nvarchar">
              <img src="{{ asset('img/articles/services.png') }}" alt="Lyricalhair">
              <div class="services-words">
                <p>We understand the customer's eagerness to receive the package sooner.After you pay,the Order Parcels are guaranteed to be sent out within 24 hours, reach your door in 1-3 days, and can be tracked at any point during the shipping process.</p>
                <p>Orders made through us are directly from the manufacturer, cutting out the middleman and resulting in significant savings. Our online platform allows for not only the convenience of shopping from home or on the go, but also contributes towards further savings for you because of the reduced overhead costs. With 25,000 products in stock, made to fit 6 different sizes, and available in 60 different colors</p>
                <p>with further variable shades, our customers truly enjoy the largest variety and the best prices on the market. Our fashions are suitable for a variety of ages, tastes, and occasions.</p> 
                <p>Individually customized hair replacement systems are also available so that you can control such factors as: the base material, base color, size, hair color, hair length, density, hair texture, curl and wave, hair direction, gray hair quantity, highlights, hairline appearance and more!</p>
              </div>
            </div>
          </div>
          {{-- 模块四 --}}
          <div class="our-mission">
            <p class="mission-title"><span>Our mission</span></p>
            <div class="mission-types">
              <div class="mission-type">
                <img class="normal" src="{{ asset('img/articles/mission-tip-1.png') }}" alt="Lyricalhair">
                <img class="hover-show" src="{{ asset('img/articles/mission-tip-1-white.png') }}" alt="Lyricalhair">
                <p class="mission-type-title">Your passion is our interest because it is a reference to your uniqueness.</p>
                <p class="mission-type-text">Looking your best has never been easier than with Lyrical Hair replacements. A younger, fresher, more exciting look at your finger tips.</p>
              </div>
              <div class="mission-type">
                <img class="normal" src="{{ asset('img/articles/mission-tip-2.png') }}" alt="Lyricalhair">
                <img class="hover-show" src="{{ asset('img/articles/mission-tip-2-white.png') }}" alt="Lyricalhair">
                <p class="mission-type-title">At LyricalHair</p>
                <p class="mission-type-text">Our mission is to provide our customers what they need to be their confident self. Our products undergo intense inspection in order to vet out any imperfections and provide you with and outstanding experience.</p>
              </div>
              <div class="mission-type">
                <img class="normal" src="{{ asset('img/articles/mission-tip-3.png') }}" alt="Lyricalhair">
                <img class="hover-show" src="{{ asset('img/articles/mission-tip-3-white.png') }}" alt="Lyricalhair">
                <p class="mission-type-text">We at Lyrical Hair are offering not only a service an experience. The opportunity define your look on your terms is too good to pass up. We are willing to negotiate to fulfill your needs. In this way we are sure that we can be there for you not only in this moment but for all of your future hair replacement requests.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scriptsAfterJs')

@endsection