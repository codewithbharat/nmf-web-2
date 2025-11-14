  <?php
  use App\Models\HomeSection;
  use App\Models\ElectionResult;
  use App\Models\MahaMukabla;
  use App\Models\Candidate;
  use App\Models\Party;
  ?>

  @php

      $showMaha = HomeSection::where('title', 'ElectionMahaSection')->where('status', 1)->exists();
      $showLive = HomeSection::where('title', 'ElectionLiveSection')->where('status', 1)->exists();
      $showExitPoll = HomeSection::where('title', 'ExitPollSection')->where('status', 1)->exists();
  @endphp

  @extends('layouts.app')
  @section('content')
      <link rel="stylesheet" href="{{ asset('/asset/css/dharm-gyan.css') }}" type="text/css" media="all" />
      <style>
          .breadcrumb {
              background: rgba(0, 0, 0, .03);
              margin-top: 30px;
              padding: 7px 20px;
              position: relative;
          }

          .section-title span {
              line-height: 36px;
              !important;
          }
      </style>

      <div class="inner-page-wrapper" style="transform: none;">
          <div id="primary" class="content-area" style="transform: none;">
              <main id="main" class="site-main" style="transform: none;">
                  <div class="cm_archive_page" style="transform: none;">

                      <div class="cm-container">
                          <div class="breadcrumb  default-breadcrumb" style="display: block;">
                              <nav role="navigation" aria-label="Breadcrumbs" class="breadcrumb-trail breadcrumbs"
                                  itemprop="breadcrumb">
                                  <ul class="trail-items" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                                      <meta name="numberOfItems" content="3">
                                      <meta name="itemListOrder" content="Ascending">
                                      <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                          class="trail-item trail-begin"><a href="/" rel="home"
                                              itemprop="item"><span itemprop="name">Home</span></a>
                                          <meta itemprop="position" content="1">
                                      </li>
                                      <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem"
                                          class="trail-item trail-end"><a
                                              href="{{ asset('/') }}{{ isset($category->site_url) ? $category->site_url : '-' }}"
                                              itemprop="item"><span
                                                  itemprop="name">{{ isset($category->name) ? $category->name : '' }}</span></a>
                                          <meta itemprop="position" content="3">
                                      </li>
                                  </ul>
                              </nav>
                          </div>
                      </div>
                      <?php
                      $bidhansabhacatname = $category->name;
                      ?>
                      @if (trim($bidhansabhacatname) === 'विधानसभा चुनाव')
                          <section class="election-section-live">
                              @if ($showExitPoll)
                                  @include('components.election-exit-poll')
                                  <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
                              @elseif($showLive)
                                  <div id="election-live-wrapper">
                                      @include('partials._election-live-section')
                                  </div>
                                  <x-horizontal-ad :ad="$data['homeAds']['home_header_ad'] ?? null" />
                              @endif

                          </section>
                      @endif
                      {{-- ============================================= --}}
                      {{-- NL1043: 08.10.2025 : added --}}
                      @if (trim($bidhansabhacatname) === 'विधानसभा चुनाव')
                          <!-- Second visit -->
                          <section class="maha-section" >
                              {{-- show mahamukabla --}}
                              @if ($showMaha)
                                  <div class="mt-3" id="maha-section-wrapper">
                                      @include('partials._maha-section')
                                  </div>
                              @endif
                          </section>
                      @endif

                      {{-- Horizontal-1 Advertise --}}
                      <x-horizontal-ad :ad="$categoryAds['category_header_ad'] ?? null" />

                      <section class="news_main_section">
                          <div class="cm-container">
                              <div class="news_main_row">
                                  <div class="col_left">
                                      <div class="news_main_wrap">
                                          <div class="nws-left">
                                              @if (count($topBlogs) > 0)
                                                  <?php
                                                  $blog = $topBlogs->first();
                                                  $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                  $symbol = $blog->link ? '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;' : '';
                                                  $truncated = $symbol . $blog->name;
                                                  //$ff = config('global.blog_images_everywhere')($blog);
                                                  $ff = cached_blog_image($blog);
                                                  ?>
                                                  <div class="nws_card">
                                                      <div class="nws_card_top dg_top">
                                                          <a
                                                              href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                              <img @if (!empty($ff)) src="{{ asset($ff) }}" @endif
                                                                  alt="{{ $blog->name }}">
                                                          </a>
                                                          <div class="category_strip">
                                                              @if (isset($cat->name) && $cat->name == 'राज्य' && isset($blog->state->name))
                                                                  <a href="{{ asset('/') }}{{ 'state' }}/{{ $blog->state->site_url }}"
                                                                      class="category">{{ $blog->state->name }}</a>
                                                                  {{-- @else
                                                                    <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                        class="category">{{ $cat->name ?? '' }}
                                                                    </a> --}}
                                                              @endif
                                                          </div>
                                                      </div>
                                                      <div class="nws_card_bottom">
                                                          <a
                                                              href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($category->site_url) ? $category->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?>
                                                          </a>
                                                      </div>
                                                      <div class="publish_wrap">
                                                          <div class="publish_dt">
                                                              <i class="fa-regular fa-calendar-days"></i>
                                                              <span>{{ $blog->created_at->format('d M, Y') }}</span>
                                                          </div>
                                                          <div class="publish_tm">
                                                              <i class="fa-regular fa-clock"></i>
                                                              <span>{{ $blog->created_at->format('h:i A') }}</span>
                                                          </div>
                                                      </div>
                                                  </div>
                                              @endif
                                          </div>

                                          <div class="nws-right">
                                              @foreach ($topBlogs->skip(1)->take(4) as $blog)
                                                  <?php
                                                  $cat = App\Models\Category::where('id', $blog->categories_ids)->first();
                                                  //$ff = config('global.blog_images_everywhere')($blog);
                                                  $ff = cached_blog_image($blog);
                                                  $symbol = $blog->link ? '<i class="fa fa-video-camera" aria-hidden="true" style="color: red;"></i>&nbsp;&nbsp;' : '';
                                                  $truncated = $symbol . $blog->name;
                                                  ?>
                                                  <div class="custom-tab-card">
                                                      <a class="custom-img-link"
                                                          href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($cat->site_url) ? $cat->site_url : '' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>">
                                                          <img @if (!empty($ff)) src="{{ asset($ff) }}" @endif
                                                              alt="{{ $blog->name }}">
                                                      </a>
                                                      <div class="custom-tab-title">
                                                          @if (isset($cat->name) && $cat->name == 'राज्य' && isset($blog->state->name))
                                                              <a href="{{ asset('/') }}{{ 'state' }}/{{ $blog->state->site_url }}"
                                                                  class="nws_article_strip">{{ $blog->state->name }}</a>
                                                              {{-- @else
                                                                <a href="{{ asset('/') }}{{ isset($cat->site_url) ? $cat->site_url : '' }}"
                                                                    class="nws_article_strip">{{ $cat->name ?? '' }}
                                                                </a> --}}
                                                          @endif
                                                          <a id="cat-t"
                                                              href="{{ asset('/') }}@if (isset($blog->isLive) && $blog->isLive != 0) live/ @endif{{ isset($category->site_url) ? $category->site_url : '-' }}/<?php echo isset($blog->site_url) ? $blog->site_url : ''; ?>"><?php echo $truncated; ?>
                                                          </a>
                                                      </div>
                                                  </div>
                                              @endforeach
                                          </div>
                                      </div>
                                      {{-- -Web Stories- --}}
                                      <?php
                                      $webStories = App\Models\WebStories::where('status', '1')->where('categories_id', $category->id)->orderBy('id', 'DESC')->limit(10)->get();
                                      ?>
                                      @if ($webStories->isNotEmpty())
                                          @include('components.category.cat-web-story', [
                                              'webStories' => $webStories,
                                          ])
                                      @endif

                                      <?php
                                      $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
                                      $stateName = '';
                                      if ($state != '') {
                                          $stateObj = App\Models\State::where('site_url', $state)->first();
                                          $stateName = isset($stateObj->name) ? $stateObj->name : '';
                                          $stateName = $stateName == 'नई दिल्ली' ? 'दिल्ली' : $stateName;
                                          $stateUrl = isset($stateObj->site_url) ? $stateObj->site_url : '';
                                      }
                                      $bidhansabha_cat_name = App\Models\Category::where('name', 'विधान सभा चुनाव')->first();
                                      $bidhansabha_cat_url = isset($bidhansabha_cat_name->site_url) ? $bidhansabha_cat_name->site_url : '';
                                      
                                      $subcat = isset($_REQUEST['subcat']) ? $_REQUEST['subcat'] : '';
                                      $subcatName = '';
                                      if ($subcat != '') {
                                          $subcatObj = App\Models\SubCategory::where('site_url', $subcat)->first();
                                          $subcatName = isset($subcatObj->name) ? $subcatObj->name : '';
                                          $subcatUrl = isset($subcatObj->site_url) ? $subcatObj->site_url : '';
                                      }
                                      
                                      $categorySlug = isset($category->site_url) ? $category->site_url : '';
                                      ?>

                                      <div class="news_sub_wrap">
                                          <div class="news_tab_t">
                                              <div class="ntab">
                                                  @if ($stateName)
                                                      <a class="newstab_title"
                                                          href="{{ asset($bidhansabha_cat_url) . '?state=' . $stateUrl }}">
                                                          {{ $stateName }}
                                                          {{ isset($category->name) ? $category->name : '' }}
                                                      </a>
                                                  @elseif ($subcatName)
                                                      <a class="newstab_title"
                                                          href="{{ asset($subcatUrl) . '?subcat=' . $subcatUrl }}">
                                                          {{ isset($category->name) ? $category->name : '' }}->{{ $subcatName }}
                                                      </a>
                                                  @else
                                                      <a class="newstab_title"
                                                          href="{{ asset($categorySlug) }}">{{ isset($category->name) ? $category->name : '' }}
                                                      </a>
                                                  @endif
                                              </div>
                                              <div class="nline">

                                              </div>
                                          </div>
                                          <ul class="nws_list" id="blog-list">
                                              @include('components.category.blog-list', [
                                                  'blogs' => $blogs,
                                                  'categoryAds' => $categoryAds,
                                                  'category' => $category,
                                              ])
                                          </ul>

                                          {{-- NL1031: 19.09.2025 : removed --}}

                                          @if ($blogs->count() > 0)
                                              <div class="text-center my-4">
                                                  <button id="load-more-btn" class="show-more-btn"
                                                      data-offset="{{ $blogs->count() }}"
                                                      data-name="{{ $category->site_url }}"
                                                      data-state="{{ request('state', '') }}"
                                                      data-subcat="{{ request('subcat', '') }}">
                                                      Show More <i class="fa-solid fa-angle-down"></i>
                                                  </button>
                                              </div>
                                          @endif
                                      </div>
                                  </div>
                                  <div class="col_right">
                                      {{-- - 10 latest articles displayed - --}}
                                      @include('components.latestStories')

                                      {{-- Vertical-Small-1 Category Advertise --}}
                                      <x-vertical-sm-ad :ad="$categoryAds['category_sidebar_vaerical_ad1'] ?? null" />

                                      {{-- Side Widgets --}}
                                      <?php
                                      $categories = [['name' => 'क्या कहता है कानून?', 'limit' => 3], ['name' => 'पॉडकास्ट', 'limit' => 1], ['name' => 'टेक्नोलॉजी', 'limit' => 5], ['name' => 'स्पेशल्स', 'limit' => 5]];
                                      ?>
                                      @foreach ($categories as $cat)
                                          <?php
                                          $category = App\Models\Category::where('name', $cat['name'])->first();
                                          $blogs = App\Models\Blog::where('status', '1')->where('categories_ids', $category->id)->orderBy('updated_at', 'DESC')->limit($cat['limit'])->get()->all();
                                          ?>
                                          @include('components.side-widgets', [
                                              'categoryName' => $cat['name'],
                                              'category' => $category,
                                              'blogs' => $blogs,
                                          ])
                                      @endforeach

                                      {{-- Vertical-Small-2 Category Advertise --}}
                                      <x-vertical-sm-ad :ad="$categoryAds['category_sidebar_vaerical_ad2'] ?? null" />

                                  </div>
                              </div>

                              {{-- NL1043: 08.10.2025 : added --}}

                              @if (trim($bidhansabhacatname) === 'विधानसभा चुनाव')
                                  <section class="election-section">
                                      <div class="cm-container">
                                          <div class="election-wrap ew-m">
                                              <div class="el-img">
                                                  <img src="{{ config('global.base_url_asset') }}asset/images/bihar-map.png"
                                                      alt="Election 2020">
                                              </div>
                                              <div class="el-row">
                                                  <div class="el-left">
                                                      <h3 class="el-title">2020 का परिणाम</h3>
                                                      <div class="result-section">
                                                          <div class="result-box nda">
                                                              <span class="title">NDA</span>
                                                              <span class="count" data-count="125">0</span>
                                                          </div>
                                                          <div class="result-box rjd">
                                                              <span class="title">RJD+</span>
                                                              <span class="count" data-count="110">0</span>
                                                          </div>
                                                          <div class="result-box ljp">
                                                              <span class="title">LJP</span>
                                                              <span class="count" data-count="1">0</span>
                                                          </div>
                                                          <div class="result-box oth">
                                                              <span class="title">OTH</span>
                                                              <span class="count" data-count="7">0</span>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <div class="el-center">
                                                      <table class="party-table">
                                                          <thead>
                                                              <tr>
                                                                  <th>Party</th>
                                                                  <th>Seats</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/bjp-logo.png"
                                                                          alt="BJP"> BJP</td>
                                                                  <td>74</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/jdu-logo.png"
                                                                          alt="JDU"> JDU</td>
                                                                  <td>43</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/rjd-logo.png"
                                                                          alt="RJD"> RJD</td>
                                                                  <td>75</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/inc-logo.png"
                                                                          alt="INC"> INC</td>
                                                                  <td>19</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/oth-logo.png"
                                                                          alt="OTH"> OTH</td>
                                                                  <td>32</td>
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </div>
                                                  <div class="el-right">

                                                      <div class="chart-container">
                                                          <div class="win-mark">
                                                              <div class="win-t"></div>
                                                              <div class="win-l"></div>
                                                          </div>
                                                          <canvas id="semiCircleChartNew" width="256"
                                                              height="170"></canvas>
                                                          <!-- <div class="legend" id="chartLegend"></div> -->
                                                          <div class="total-seats">
                                                              <p>Total Seats</p>
                                                              <h3>243 seats</h3>
                                                          </div>
                                                      </div>

                                                  </div>
                                              </div>
                                          </div>

                                          <div class="election-wrap ew-m2">
                                              <div class="el-img">
                                                  <img src="{{ config('global.base_url_asset') }}asset/images/bihar-map.png"
                                                      alt="Election 2020">
                                              </div>
                                              <h3 class="el-title">2020 का परिणाम</h3>
                                              <div class="el-row-m">
                                                  <div class="el-left-m">
                                                      <div class="result-section">
                                                          <div class="result-box nda">
                                                              <span class="title">NDA</span>
                                                              <span class="count" data-count="125">0</span>
                                                          </div>
                                                          <div class="result-box rjd">
                                                              <span class="title">RJD+</span>
                                                              <span class="count" data-count="110">0</span>
                                                          </div>
                                                          <div class="result-box ljp">
                                                              <span class="title">LJP</span>
                                                              <span class="count" data-count="1">0</span>
                                                          </div>
                                                          <div class="result-box oth">
                                                              <span class="title">OTH</span>
                                                              <span class="count" data-count="7">0</span>
                                                          </div>
                                                      </div>
                                                      <table class="party-table">
                                                          <thead>
                                                              <tr>
                                                                  <th>Party</th>
                                                                  <th>Seats</th>
                                                              </tr>
                                                          </thead>
                                                          <tbody>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/bjp-logo.png"
                                                                          alt="BJP"> BJP</td>
                                                                  <td>74</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src=" {{ config('global.base_url_asset') }}asset/images/jdu-logo.png"
                                                                          alt="JDU"> JDU</td>
                                                                  <td>43</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/rjd-logo.png"
                                                                          alt="RJD"> RJD</td>
                                                                  <td>75</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/inc-logo.png"
                                                                          alt="INC"> INC</td>
                                                                  <td>19</td>
                                                              </tr>
                                                              <tr>
                                                                  <td><img src="{{ config('global.base_url_asset') }}asset/images/oth-logo.png"
                                                                          alt="OTH"> OTH</td>
                                                                  <td>32</td>
                                                              </tr>
                                                          </tbody>
                                                      </table>
                                                  </div>
                                                  <div class="el-right-m">

                                                      <div class="chart-container2">
                                                          <div class="win-mark">
                                                              <div class="win-t"></div>
                                                              <div class="win-l"></div>
                                                          </div>
                                                          <canvas id="semiCircleChart2"></canvas>
                                                          <!-- <div class="legend" id="chartLegend"></div> -->
                                                          <div class="total-seats">
                                                              <p>Total Seats</p>
                                                              <h3>243 seats</h3>
                                                          </div>
                                                      </div>


                                                  </div>
                                                  .
                                              </div>
                                          </div>

                                      </div>

                                  </section>
                              @endif
                              {{-- Horizontal-2 Advertise --}}
                              <x-horizontal-ad :ad="$categoryAds['category_bottom_ad'] ?? null" />

                          </div>
                      </section>

                  </div>
              </main>
          </div>
      </div>
      {{-- added===== --}}
      {{--   @php
          $results3 = $topParties->take(4)->map(function ($p) {
              return [
                  'party_name' => $p->party_name,
                  'abbreviation' => $p->abbreviation,
                  'seats_won' => $p->seats_won,
              ];
          });
      @endphp  --}}


      {{-- <script>
          const results3 = @json($results3);
      </script>   --}}

      {{-- added end====== --}}

      {{-- NL1031: 19.09.2025 : added chart js --}}
      {{-- Chart.js --}}
      <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.0"></script>
      <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

      <script>
          /**
           * Refreshes the "Maha Muqabala" section by fetching new HTML,
           * replacing the content of '#maha-section-wrapper', and 
           * re-initializing its components (charts, swipers).
        */
                  async function refreshMahaSection() {
                      try {
                          const response = await fetch('/refresh-maha-section');
                          const html = await response.text();

                          // Find the wrapper and replace its content
                          const wrapper = document.getElementById('maha-section-wrapper');
                          if (wrapper) {
                              wrapper.innerHTML = html;
                          }

                          // Re-initialize components for this section
                          // Assumes initializeMahaSectionComponents() is defined elsewhere
                          if (typeof initializeMahaSectionComponents === 'function') {
                              initializeMahaSectionComponents();
                          }

                      } catch (error) {
                          console.error('Error refreshing Maha Muqabala:', error);
                      }
                  }

                  /**
                   * Refreshes the "Election Live" section by fetching new HTML,
                   * replacing the content of '#election-live-wrapper', and 
                   * re-initializing its components.
                   */
                  async function refreshLiveSection() {
                      try {
                          // Fetch from the new route
                          const response = await fetch('/refresh-live-section');
                          const html = await response.text();

                          // Target the specific wrapper for this section
                          const wrapper = document.getElementById('election-live-wrapper');
                          if (wrapper) {
                              wrapper.innerHTML = html;
                          }

                          // Re-initialize components for this section
                          // Assumes initializeLiveSectionComponents() is defined elsewhere
                          if (typeof initializeLiveSectionComponents === 'function') {
                              initializeLiveSectionComponents();
                          }

                      } catch (error) {
                          console.error('Error refreshing Election Live:', error);
                      }
                  }

                  // Call refresh functions every 10 seconds (10000 ms).
                  // Will only invoke the relevant refresh if its wrapper exists.
                  setInterval(refreshMahaSection, 8000); // 5 seconds
        setInterval(refreshLiveSection, 8000); // 8 seco
              </script>

    <script>
          // NL1043: 14.11.2025 : Refactored for AJAX refresh
          
          // 1. Define data for all charts (except results3, which comes from PHP)
          const resultsNew = [{
                  party_name: "NDA",
                  seats_won: 95,
                  abbreviation: "nda"
              },
              {
                  party_name: "RJD+",
                  seats_won: 70,
                  abbreviation: "rjd"
              },
              {
                  party_name: "OTH",
                  seats_won: 20,
                  abbreviation: "oth"
              }
          ];

          const results1 = [{
                  party_name: "NDA",
                  seats_won: 87,
                  abbreviation: "nda"
              },
              {
                  party_name: "RJD+",
                  seats_won: 53,
                  abbreviation: "rjd"
              },
              {
                  party_name: "LJP",
                  seats_won: 5,
                  abbreviation: "ljp"
              },
              {
                  party_name: "OTH",
                  seats_won: 9,
                  abbreviation: "oth"
              }
          ];

          const results2 = [{
                  party_name: "NDA",
                  seats_won: 87,
                  abbreviation: "nda"
              },
              {
                  party_name: "RJD+",
                  seats_won: 53,
                  abbreviation: "rjd"
              },
              {
                  party_name: "LJP",
                  seats_won: 5,
                  abbreviation: "ljp"
              },
              {
                  party_name: "OTH",
                  seats_won: 9,
                  abbreviation: "oth"
              }
          ];
          
          // Note: 'results3' is now defined in a <script> block just above this one.
          
          
          // 2. Make helper functions global so all scripts can use them

          /**
           * Registers ChartDataLabels plugin if not already registered.
           */
          function registerChartPlugins() {
              if (window.Chart && window.ChartDataLabels && !Chart._nmfDataLabelsRegistered) {
                  Chart.register(ChartDataLabels);
                  Chart._nmfDataLabelsRegistered = true;
              }
          }

          /**
           * Creates or updates a semi-circle chart on a given canvas.
           */
          function createSemiCircleChart(canvasId, results, options = {}) {
              registerChartPlugins(); // Ensure plugins are ready
              
              const canvas = document.getElementById(canvasId);
              if (!canvas) return null; // Safely skip if canvas doesn't exist

              // Destroy previous chart instance if it exists
              if (canvas._chartInstance) {
                  try {
                      canvas._chartInstance.destroy();
                  } catch (e) {}
                  canvas._chartInstance = null;
              }

              // Filter out LJP as your original code does
              const filteredResults = results.filter(r => r.abbreviation.toLowerCase() !== 'ljp');
              const labels = filteredResults.map(r => r.party_name);
              const values = filteredResults.map(r => r.seats_won);
              const colorMap = {
                  'nda': '#fd6101',
                  'rjd': '#13B605',
                  'jsp': '#FABB00',
                  'oth': '#D13A37'
              };
              const colors = filteredResults.map(r => colorMap[r.abbreviation.toLowerCase()] || '#13B605');
              const aspectRatio = (typeof options.aspectRatio !== 'undefined') ?
                  options.aspectRatio :
                  (window.innerWidth < 768 ? 1 : 1.5);

              const config = {
                  type: 'doughnut',
                  data: {
                      labels: labels,
                      datasets: [{
                          data: values,
                          backgroundColor: colors,
                          borderWidth: 2,
                          borderColor: 'white',
                          hoverOffset: 15,
                          borderRadius: 4
                      }]
                  },
                  options: {
                      responsive: true,
                      maintainAspectRatio: true,
                      aspectRatio: aspectRatio,
                      rotation: -90,
                      circumference: 180,
                      cutout: options.cutout || '60%',
                      animation: {
                          duration: options.duration || 600
                      },
                      plugins: {
                          legend: {
                              display: false
                          },
                          tooltip: {
                              enabled: false
                          },
                          datalabels: {
                              color: 'black',
                              font: {
                                  weight: 'bold',
                                  size: options.datalabelSize || 14
                              },
                              formatter: () => ''
                          }
                      }
                  },
                  plugins: []
              };

              if (window.ChartDataLabels) config.plugins.push(ChartDataLabels);
              canvas._chartInstance = new Chart(canvas.getContext('2d'), config);
              return canvas._chartInstance;
          }

          /**
           * Initializes components for the 'Maha Muqabala' section.
           * This includes chart 'semiCircleChart3' and the '.mh-carousel' swiper.
           */
          function initializeMahaSectionComponents() {
              // Check if results3 is defined before trying to use it
              if (typeof results3 !== 'undefined') {
                  createSemiCircleChart('semiCircleChart3', results3, {
                      duration: 500
                  });
              }

              const mhCarousel = document.querySelector('.mh-carousel');
              if (mhCarousel) {
                   // Destroy existing swiper instance if it exists to prevent conflicts
                   if (mhCarousel.swiper) {
                        mhCarousel.swiper.destroy(true, true);
                   }
                  new Swiper('.mh-carousel', {
                      loop: true,
                      navigation: {
                          nextEl: '.mh-button-next',
                          prevEl: '.mh-button-prev',
                      },
                      autoplay: {
                          delay: 3000,
                          disableOnInteraction: false,
                      },
                  });
              }
          }

          /**
           * Initializes components for the 'Election Live' section.
           * (Add any chart/swiper init code for this section here)
           */
          function initializeLiveSectionComponents() {
              // e.g., createSemiCircleChart('liveChartCanvas', liveResults);
          }
          
          /**
           * Initializes components that do NOT refresh.
           * Runs only once on page load.
           */
          function initializeStaticComponents() {
               // ---------------- COUNTER ----------------
              const counters = document.querySelectorAll(".count");
              counters.forEach(counter => {
                  let target = +counter.getAttribute("data-count");
                  let current = 0;
                  let increment = Math.ceil(target / 50);

                  let interval = setInterval(() => {
                      current += increment;
                      if (current >= target) {
                          current = target;
                          clearInterval(interval);
                      }
                      counter.textContent = current.toString().padStart(2, '0');
                  }, 30);
              });
              
              // ---------------- INIT STATIC CHARTS ----------------
              createSemiCircleChart('semiCircleChartNew', resultsNew, {
                  duration: 500
              });
              createSemiCircleChart('semiCircleChart2', results2, {
                  duration: 500
              });
          }

          // 3. Run initializers on DOMContentLoaded
          document.addEventListener("DOMContentLoaded", function() {
              initializeStaticComponents();
              initializeMahaSectionComponents(); // Run on first load
              initializeLiveSectionComponents(); // Run on first load

              // ---------------- RE-RENDER ON RESIZE ----------------
              let resizeTimer;
              window.addEventListener('resize', function() {
                  clearTimeout(resizeTimer);
                  resizeTimer = setTimeout(function() {
                      // Re-init ALL charts
                      initializeStaticComponents();
                      initializeMahaSectionComponents();
                      initializeLiveSectionComponents();
                  }, 200);
              });
          });
          
      </script>

      <script>
          const swipernew = new Swiper('.swiper2', {
              direction: 'horizontal',
              loop: true,
              slidesPerView: 5,
              spaceBetween: 10,


              pagination: {
                  el: '.swiper-pagination',
                  clickable: true,
              },

              navigation: {
                  nextEl: '.swiper-button-next',
                  prevEl: '.swiper-button-prev',
              },


              scrollbar: {
                  el: '.swiper-scrollbar',
              },


              breakpoints: {
                  640: {
                      slidesPerView: 2,
                      spaceBetween: 10,
                  },
                  768: {
                      slidesPerView: 3,
                      spaceBetween: 20,
                  },
                  1024: {
                      slidesPerView: 5,
                      spaceBetween: 10,
                  },
              },
          });
      </script>


  @endsection
