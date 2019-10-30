@extends('layouts.app')
@section('title', (App::isLocale('zh-CN') ? 'Contact us' : 'Contact us') . ' - ' . \App\Models\Config::config('title'))
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
                <img class="normal-logo" src="{{ asset('img/articles/about-logo.png') }}" alt="Lyricalhair">
                <img class="active-logo" src="{{ asset('img/articles/about-logo-active.png') }}" alt="Lyricalhair">
                <a href="{{ route('about_lyricalhair') }}">About Us</a>
              </div>
              <div class="navigation-type active">
                <img class="normal-logo" src="{{ asset('img/articles/contact-logo.png') }}" alt="Lyricalhair">
                <img class="active-logo" src="{{ asset('img/articles/contact-logo-active.png') }}" alt="Lyricalhair">
                <a href="{{ route('seo_url', ['slug' => 'contact_us']) }}">Contact Us</a>
              </div>
              <div class="navigation-type">
                <img class="normal-logo" src="{{ asset('img/articles/why-logo.png') }}" alt="Lyricalhair">
                <img class="active-logo" src="{{ asset('img/articles/why-logo-active.png') }}" alt="Lyricalhair">
                <a href="{{ route('why_lyricalhair') }}">Why Lyricalhair</a>
              </div>
            </div>
        </div>
        {{-- 文章内容 --}}
        <div class="article-nvarchar">
          {{-- 文章大标题 --}}
          <div class="nvarchar-title">
            <span>Contact Us</span>
          </div>
          {{-- 具体文本 --}}
          <div class="nvarchar-modules">
             {{-- 模块一 --}}
            <div class="about-us-part1">
              <div class="aboutus-part1-left">
                <img src="{{ asset('img/articles/contact_us.png') }}" alt="Lyricalhair">
              </div>
              <div class="aboutus-part1-right">
                <p><span>Contact Us</span></p>
                <p>Welcome! Lyricalhair has a dedicated online customer service team, so we guarantee a fast and accurate response to your inquiry.</p>
                <p>Lyricalhair is based in China with branches in the US and Dubai.Our contact hours are from Monday to Friday from 8:30 am to 5:30 pm. You will receive an immediate response at this local time.</p>
                <p>During weekends and public holidays, email support continues, and we endeavor to answer all emails within 12 hours.</p>
              </div>
            </div>
            {{-- 模块二 --}}
            <div class="contact-information">
              <p class="contact-information-title"><span></span>Our contact information is as follows:</p>
              <div class="lyrical-ch information-part">
                <div class="information-part-name">LYRICALHAIR CHINA</div>
                <div class="information-part-describe">
                  <div class="local-info">
                    <img src="{{ asset('img/articles/local-info.png') }}" alt="Lyricalhair">
                  </div>
                  <div>
                    <p>Factory Add: Laixi Jianshan Industry Zone, Qingdao,China</p> 
                    <p>Office Add: Apartment 1-2-201 NO.9, Mai Dao Rd., Laoshan District,Qingdao,China,Zipcode:266000</p>
                  </div>
                </div>
                <ul class="information-part-way">
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/dubai-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>Office phone:+86-532-85878587</span>
                  </li>
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/email-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>Email: support@lyricalhair.com</span>
                  </li>
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/skype-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>Skype: Lyrical Hair</span>
                  </li>
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/wechat-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>WhatsApp: +8615764271924</span>
                  </li>
                </ul>
              </div>
              <div class="lyrical-usa information-part">
                <div class="information-part-name">LYRICALHAIR USA</div>
                <div class="information-part-describe">
                  <div class="local-info">
                    <img src="{{ asset('img/articles/local-info.png') }}" alt="Lyricalhair">
                  </div>
                  <div>
                    <p>Add: 2001 Santa Anita Ave.,#101,South El Monte Los Angelos, CA9</p>
                  </div>
                </div>
                <ul class="information-part-way">
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/dubai-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>Office phone: +1 626 416 5476</span>
                  </li>
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/email-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>Email: lyricalhair@hotmail.com</span>
                  </li>
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/phone-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>Cell phone: +1 323 974 7170(Timothy Starks)</span>
                  </li>
                </ul>
              </div>
              <div class="lyrical-dubai information-part">
                <div class="information-part-name">LYRICALHAIR DUBAI</div>
                <div class="information-part-describe">
                  <div class="local-info">
                    <img src="{{ asset('img/articles/local-info.png') }}" alt="Lyricalhair">
                  </div>
                  <div>
                      <p>Add: Shop No: 3 ABDUL RAHMAN BIN SALEH BUILDING,NEAR CITY STAR HOTEL,</p>
                      <p>SALAHUDHEEN STREET,DEIRA Dubai .</p>
                  </div>
                </div>
                <ul class="information-part-way">
                  <li>
                    <div class="icon-masks">
                      <img src="{{ asset('img/articles/dubai-icon.png') }}" alt="Lyricalhair">
                    </div>
                    <span>Office phone: +971-526058218</span>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> 
@endsection

@section('scriptsAfterJs')

@endsection