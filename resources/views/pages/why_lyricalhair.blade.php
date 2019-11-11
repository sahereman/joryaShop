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
            <p class="why-title"><span>WHY CHOOSE LYRICALHAIR</span></p>
            {{-- 模块一 --}}
            <div class="why-part1">
              <p>Lyricalhair operates globally through our online platforms to provide hair replacement systems at the best price available. Before we established this online mall, we mainly carried out B2B transactions with wholesalers and distributors but with the development of cross-border e-commerce, more and more individuals will bypass intermediaries and go directly to shopping online. To cater to these customers, we gradually immersed into the platform marketing of eBay, Amazon, and ALI Express using different brand names. So far good results have been achieved on these platforms with eBay and Amazon being two of our major channels having the largest number of reach on today's digitally-inclined market. For more and more customers to better enjoy our LyricalHairbrand service, we now build our own online mall.</p>
              {{-- <div class="why-part-item"><a href="#part2Item1"><span>Non-Surgical Hair Replacement</span></a></div>
              <div class="why-part-item"><a href="#part2Item2"><span>Ready To Wear</span></a></div>
              <div class="why-part-item"><a href="#part2Item3"><span>Affordable Factory Prices</span></a></div>
              <div class="why-part-item"><a href="#part2Item4"><span>30-Day Money-Back Guarantee</span></a></div>
              <div class="why-part-item"><a href="#part2Item5"><span>Easy To Order Online</span></a></div>
              <div class="why-part-item"><a href="#part2Item6"><span>Safe Online Payment</span></a></div>
              <div class="why-part-item"><a href="#part2Item7"><span>Fast Worldwide Free Shipping</span></a></div>
              <div class="why-part-item"><a href="#part2Item8"><span>Professional Customer Service</span></a></div>
              <div class="why-part-item"><a href="#part2Item9"><span>Frequent Order Status Updates</span></a></div> --}}
            </div>
            {{-- 模块二 --}}
            <div class="why-part2">
              <div class="why-part2-item" id="part2Item1">
                <a href="target-fix"></a>
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon1.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Exquisite Manufacturing Expertise</span>
                </div>
                <div class="why-part2-item-content">
                  <p>We have a production history of 20 years.In  these 20 years, we have grown.Twenty years of time has not only left our enterprise with the production experience of the predecessors, but also left us skilled workers.They are a valuable asset to our handmade industry. They have worked here for ten, twenty years.They inherit the previous exquisite workmanship, do a good job skills and carry out research and development and innovation. Just because of them,we have the confidence to provide customers with unparalleled goods.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item2">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon2.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Guaranteed High Quality Products </span>
                </div>
                <div class="why-part2-item-content">
                  <p>Our global online retail company manufactures its products in our own factory. With that being said, we are able to closely monitor and assure the quality of the production of our hair replacement systems. As a result of our polished craftsmanship, we are rated as the Best Wig Manufacturer in China by the Wig Association of China and have obtained ISO9001 certification and awarded BBB certification by American trade association.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item3">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon3.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Affordable Factory Prices</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Since we have our own factory, we also get to eliminate unnecessary costs typical of working through a middleman. Because of that you are guaranteed to receive the best quality products at the lowest price! </p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item4">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon4.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Customer-centric Services</span>
                </div>
                <div class="why-part2-item-content">
                  <p>We do not just focus on the monetary returns that we earn in this industry for we also give importance to every connection we get to build with every client and partner that we have. Thus in every action we take, we have you in mind. Through offering special services (Custom Order, Repaired Order, Duplicated Order) to you, it is our goal to provide you with the hair system you have envisioned and make you feel satisfied with every order you make with us.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item5">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon5.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Ready To Wear</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Products made by Lyricalhair are 100% human hair (Indian hair, European hair, Cuticle-intact Virgin hair) making them of the highest quality. Our commitment to making your hair ready to wear upon arrival means that we are also able to cut and style your hair system before shipment. Orders can be placed at any time and location of your convenience. Just shop from our online selection and in a few days you will have a hair system that can be easily attached by yourself.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item6">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon6.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Easy To Order Online</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Our website was constructed to allow you to navigate freely with our user-friendly design. The search tools, multiple filters, and tags will help you to specify what style you are looking for. Any order can be easily placed in a matter of minutes, regardless of your specifications, with the help of our self-guided order form.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item7">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon7.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Safe Online Payment</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Lyricalhair accepts payment through major credit and debit cards, wire transfer, Western Union, and PayPal, for your convenience. Our site is fully secured by NORTON from Symantec so you can be rest assured that your payment details and personal information sent through our website is safeguarded and is kept strictly confidential.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item8">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon8.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Fast Worldwide Shipping</span>
                </div>
                <div class="why-part2-item-content">
                  <p>We work in cooperation with logistic service providers with internationally recognized reputations such as DHL, FedEx, and UPS, thus allowing us to ship to over 200 countries around the world. Additionally, because of our warehouse's convenient location in Los Angelos, California, you can expect to receive your hair systems in 2-4 days, and in some cases, in even just one day! We value the need for discretion and for this reason our shipments are sent in unlabeled packages, protecting your privacy while purchasing our hair replacement systems.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item9">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon9.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Frequent Order Status Updates</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Since we use internationally recognized logistic services, we are able to keep you informed of your order and shipping status. You can always contact us to check your order status and have no need to fear that the shipment will not arrive.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item10">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon10.png') }}" alt="Lyricalhair">
                  </div>
                  <span>Professional Customer Service</span>
                </div>
                <div class="why-part2-item-content">
                  <p>Lyricalhair has a fine reputation for offering customer service that is friendly and comprehensive throughout each part of your journey. You might also like to take advantage of our online live-chat service, where our skilled and experienced professionals can give you sound advice even before ordering our hair replacement system. Our service does not end there for our customer service representatives will continue to be on-hand to answer any further questions you may have even after the order is placed.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item11">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon11.png') }}" alt="Lyricalhair">
                  </div>
                  <span>30-Day Money-Back Guarantee</span>
                </div>
                <div class="why-part2-item-content">
                  <p>A 30-day guarantee accompanies each product, insuring that if you are unsatisfied with your hair system in any way once you have received it, then we are prepared to repair, remake, or give you a refund of your purchase at no cost to you.</p>
                </div>
              </div>
              <div class="why-part2-item" id="part2Item12">
                <div class="why-part2-item-title">
                  <div class="item-title-img">
                    <img src="{{ asset('img/articles/why-icon12.png') }}" alt="Lyricalhair">
                  </div>
                  <span>We Pay It Forward</span>
                </div>
                <div class="why-part2-item-content">
                  <p>In the celebration of the development and success of our company, we have already participated in various Charity events and have given back to the Community. Seeing smiles plastered on every individual's faces and being able to lighten someone's day never failed to bring joy and warmth into our hearts. Because of this, we are inspired to Do Better and Be Better for us to be able to give more again and again to the Community. To be able to share our blessings with others is truly the greatest achievement we have obtained and we wish to continue doing so as the company grows. </p>
                  <p>What groups have we donated to:  Cancer Battles Supporters, Hello Generous!, Everybody Gets to Eat Inc. and many more. </p>
                </div>
              </div>
            </div>
            {{-- 模块三 --}}
            <div class="why-part3">
                {{-- 工厂轮播 --}}
                <div class="Factory-by">
                  <div class="swiper-container" id="FactoryBy">
                      <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="slide-img">
                                <img src="{{ asset('img/articles/why-pic.png') }}" alt="Lyricalhair">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="slide-img">
                                <img src="{{ asset('img/articles/why-pic.png') }}" alt="Lyricalhair">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="slide-img">
                                <img src="{{ asset('img/articles/why-pic.png') }}" alt="Lyricalhair">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="slide-img">
                                <img src="{{ asset('img/articles/why-pic.png') }}" alt="Lyricalhair">
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="slide-img">
                                <img src="{{ asset('img/articles/why-pic.png') }}" alt="Lyricalhair">
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('scriptsAfterJs')
<script src="{{ asset('js/swiper/js/swiper.min.js') }}"></script>
<script type="text/javascript">
  var FactoryBywiper = new Swiper('#FactoryBy', {
      autoplay: true,
      // slidesPerView: 4,
      // spaceBetween: 30,
      loop : true,
      breakpoints: { 
          //当宽度小于等于320
          320: {
          slidesPerView: 1,
          spaceBetween: 10
          },
      //当宽度小于等于480
          480: { 
          slidesPerView: 1,
          spaceBetween: 20
          },
          //当宽度小于等于640
          640: {
          slidesPerView: 1,
          spaceBetween: 30
          }
      }
  });
  // 鼠标移入停止自动滚动
$('.swiper-slide').mouseenter(function() {
  FactoryBywiper.autoplay.stop();
})
// 鼠标移出开始自动滚动
$('.swiper-slide').mouseleave(function() {
  FactoryBywiper.autoplay.start();
})
</script>
@endsection