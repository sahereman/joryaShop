@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? 'Why lyricalhair' : 'Why lyricalhair') . ' - ' . \App\Models\Config::config('title'))
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
              <div class="navigation-type">
                <a href="{{ route('about_us') }}">
                  <img class="normal-logo" src="{{ asset('img/articles/about-logo.png') }}" alt="Lyricalhair">
                  <img class="active-logo" src="{{ asset('img/articles/about-logo-active.png') }}" alt="Lyricalhair">
                  <p class="link-button">About Us</p>
                </a>
              </div>
              <div class="navigation-type">
                <a href="{{ route('seo_url', ['slug' => 'contact_us']) }}">
                    <img class="normal-logo" src="{{ asset('img/articles/contact-logo.png') }}" alt="Lyricalhair">
                    <img class="active-logo" src="{{ asset('img/articles/contact-logo-active.png') }}" alt="Lyricalhair">
                    <p class="link-button" >Contact Us</p>
                </a>
              </div>
              <div class="navigation-type active">
                <a href="{{ route('why_lyricalhair') }}">
                  <img class="normal-logo" src="{{ asset('img/articles/why-logo.png') }}" alt="Lyricalhair">
                  <img class="active-logo" src="{{ asset('img/articles/why-logo-active.png') }}" alt="Lyricalhair">
                  <p class="link-button">Why Lyricalhair</p>
                </a>
              </div>
            </div>
        </div>
        {{-- 文章内容 --}}
        <div class="article-nvarchar">
          {{-- 文章大标题 --}}
          <div class="nvarchar-title">
            <span>Why Lyricalhair</span>
          </div>
          {{-- 具体文本 --}}
          <div class="nvarchar-modules">
            <p class="why-title"><span>Why Lyricalhair</span></p>
            {{-- 模块一 --}}
            <div class="why-part1">
              <div class="why-part-item"><a href="#part2Item1"><span>Non-Surgical Hair Replacement</span></a></div>
              <div class="why-part-item"><a href="#part2Item2"><span>Ready To Wear</span></a></div>
              <div class="why-part-item"><a href="#part2Item3"><span>Affordable Factory Prices</span></a></div>
              <div class="why-part-item"><a href="#part2Item4"><span>30-Day Money-Back Guarantee</span></a></div>
              <div class="why-part-item"><a href="#part2Item5"><span>Easy To Order Online</span></a></div>
              <div class="why-part-item"><a href="#part2Item6"><span>Safe Online Payment</span></a></div>
              <div class="why-part-item"><a href="#part2Item7"><span>Fast Worldwide Free Shipping</span></a></div>
              <div class="why-part-item"><a href="#part2Item8"><span>Professional Customer Service</span></a></div>
              <div class="why-part-item"><a href="#part2Item9"><span>Frequent Order Status Updates</span></a></div>
            </div>
            {{-- 模块二 --}}
            <div class="why-part2">
              <div class="why-part2-item" id="part2Item1">
                <a href="target-fix"></a>
                <div class="why-part2-item-title">
                  <span>Non-Surgical Hair Replacement</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Lyricalhair operates globally through our online platform to provide hair replacement solutions at the best price available. Our solutions are pain-free, long-term, and without the hassle of visiting consultants or salons. Orders can be placed at any time and location if your convenience. Just shop from our online selection in a few days you will have hair that can be easily attached by yourself.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item2">
                <div class="why-part2-item-title">
                  <span>Ready To Wear</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Products made by Lyricalhair are 100% human hair (Indian hair, European hair, or cuticle intact virgin hair) making them of the highest quality. Our commitment to making your hair ready to wear upon arrival means that we are also able to cut and style your hair system before shipment.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item3">
                <div class="why-part2-item-title">
                  <span>Affordable Factory Prices</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Our global online retail company manufactures its product in our own factory. As a result we can control quality and eliminate unnecessary costs typical of working through a middleman. This means that you are guaranteed to receive the best quality product at the lowest price!</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item4">
                <div class="why-part2-item-title">
                  <span>30-Day Money-Back Guarantee</span>
                </div>
                <div class="why-part2-item-content">
                  <p>A 30-day guarantee accompanies each product, insuring that if you're unsatisfied with your hair system in any way once you have received it, then we are prepared to repair, remake, or give you a refund your purchase at no cost to you.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item5">
                <div class="why-part2-item-title">
                  <span>Easy To Order Online</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Our website was constructed to allow you to navigate freely with our user friendly design. The search tools, multiple filters, and tags will help you to specify what style you are looking for. Any order can be easily placed in a matter of minutes, regardless of your specifications, with the help of our self guided order form.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item6">
                <div class="why-part2-item-title">
                  <span>Safe Online Payment</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Lyricalhair accepts payment through major credit and debit cards, wire transfer, Western Union, and PayPal, for your convenience. Our site is fully secured by NORTON from Symantec so you can rest assured that your payment information and any other personal information sent through our website is safe and sound.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item7">
                <div class="why-part2-item-title">
                  <span>Fast Worldwide Free Shipping</span>
                </div>
                <div class="why-part2-item-content">
                  <p>We work in cooperation with logistic service providers with internationally recognized reputations such as DHL, FedEx, and UPS, thus allowing us to ship to over 200 countries around the world. Additionally, because of our warehouse’ convenient location in Los Angelos, California, you can expect to receive your hair system in 2-4 days, and in some cases in even just one day! We value the need for discretion and for this reason our shipments are sent in unlabeled packages, protecting your privacy while purchasing our hair replacement systems.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item8">
                <div class="why-part2-item-title">
                  <span>Professional Customer Service</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Lyricalhair has a fine reputation for offering customer service that is friendly and comprehensive throughout each part of your journey. You might also like to take advantage of our online live-chat service, where our skilled and experienced professionals can give you sound advice even before ordering our hair replacement system. Our service does not end there. Customer service representatives continue to be on-hand to answer any further questions you may have even after the order is placed.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item9">
                <div class="why-part2-item-title">
                  <span>Frequent Order Status Updates</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Since we use internationally recognized logistic services, we are able to keep you informed of your order and shipping status. You can always contact us to check your order status and have no need to fear that the shipment will not arrive.</p>
                </div>
              </div>
            </div>
            {{-- 模块三 --}}
            <div class="why-part3">
                <img src="{{ asset('img/articles/why-pic.png') }}" alt="Lyricalhair">
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('scriptsAfterJs')

@endsection