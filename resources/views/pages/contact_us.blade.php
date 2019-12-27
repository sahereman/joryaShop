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
                <a href="{{ route('about_us') }}">
                  <img class="normal-logo" src="{{ asset('img/articles/about-logo.png') }}" alt="Lyricalhair">
                  <img class="active-logo" src="{{ asset('img/articles/about-logo-active.png') }}" alt="Lyricalhair">
                  <p class="link-button">About Us</p>
                </a>
              </div>
              <div class="navigation-type active">
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
                    <p>Office Add: Apartment 1-2-201, NO. 9 Mai Dao Rd., Laoshan District, Qingdao, China, Zipcode: 266000</p>
                  </div>
                </div>
                <div class="mapinfo">
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
                        <span>Email: info@lyricalhair.com</span>
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
                        <span>WhatsApp: +86-18661937606</span>
                      </li>
                    </ul>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1612.6017091807532!2d120.4228308!3d36.0641422!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x359610d9dce3cb4b%3A0x264f0b1586ab527a!2s9%20Maidao%20Rd%2C%20Laoshan%20Qu%2C%20Qingdao%20Shi%2C%20Shandong%20Sheng%2C%20China%2C%20266031!5e0!3m2!1sen!2shk!4v1573024999154!5m2!1sen!2shk" width="50%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                </div>
              </div>
              <div class="lyrical-usa information-part">
                <div class="information-part-name">LYRICALHAIR USA</div>
                <div class="information-part-describe">
                  <div class="local-info">
                    <img src="{{ asset('img/articles/local-info.png') }}" alt="Lyricalhair">
                  </div>
                  <div>
                    <p>Add: 2001 Santa Anita Ave.,#101,South El Monte Los Angelos, CA, USA</p>
                  </div>
                </div>
                <div class="mapinfo">
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
                  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d105836.25195498398!2d-118.0647357904192!3d34.008381846651424!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80c2d0ea125b52ed%3A0xc84a67922326234a!2sLyrical%20Human%20Hair%20System%20Extensions%20Wigs%20Toupee%20Manufacture%20and%20Wholesaler!5e0!3m2!1sen!2shk!4v1573026457698!5m2!1sen!2shk" width="50%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
                </div>
              </div>
              <div class="lyrical-dubai information-part">
                <div class="information-part-name">LYRICALHAIR DUBAI</div>
                <div class="information-part-describe">
                  <div class="local-info">
                    <img src="{{ asset('img/articles/local-info.png') }}" alt="Lyricalhair">
                  </div>
                  <div>
                      <p>Add: Shop No. 3, ABDUL RAHMAN BIN SALEH BUILDING, near to CITY STAR HOTEL,</p>
                      <p>SALAHUDHEEN STREET, DEIRA, Dubai</p>
                  </div>
                </div>
                <div class="mapinfo">
                  <ul class="information-part-way">
                    <li>
                      <div class="icon-masks">
                        <img src="{{ asset('img/articles/dubai-icon.png') }}" alt="Lyricalhair">
                      </div>
                      <span>Office phone: +971-526058218</span>
                    </li>
                  </ul>
                  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d379.2461828320758!2d55.3182986215818!3d25.269977510519933!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f5cca0ba0500f%3A0x546c6920197a8ccc!2sLyrical%20Human%20Hair%20Wefts%20and%20Toupee!5e0!3m2!1sen!2shk!4v1573026167990!5m2!1sen!2shk" width="50%" height="450" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
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