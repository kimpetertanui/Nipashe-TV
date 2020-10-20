package com.app.livetvstrean.activities;

import android.content.Intent;
import android.content.res.Resources;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import com.google.android.material.appbar.AppBarLayout;
import com.google.android.material.appbar.CollapsingToolbarLayout;
import androidx.coordinatorlayout.widget.CoordinatorLayout;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;
import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.app.livetvstrean.Config;
import com.app.livetvstrean.R;
import com.app.livetvstrean.callbacks.CallbackChannelDetail;
import com.app.livetvstrean.databases.DatabaseHandlerFavorite;
import com.app.livetvstrean.models.Channel;
import com.app.livetvstrean.rests.ApiInterface;
import com.app.livetvstrean.rests.RestAdapter;
import com.app.livetvstrean.utils.Constant;
import com.app.livetvstrean.utils.NetworkCheck;
import com.app.livetvstrean.utils.Tools;
import com.google.android.gms.ads.AdListener;
import com.google.android.gms.ads.AdView;
import com.google.android.gms.ads.InterstitialAd;
import com.google.android.gms.ads.MobileAds;
import com.squareup.picasso.Picasso;

import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActivityOneSignalDetail extends AppCompatActivity {

    String id;
    CoordinatorLayout lyt_content;
    View lyt_parent, lyt_progress;
    Channel post;
    private Call<CallbackChannelDetail> callbackCall = null;
    DatabaseHandlerFavorite databaseHandler;
    FloatingActionButton floatingActionButton;
    ImageView channel_image;
    TextView channel_name, channel_category;
    WebView channel_description;
    CollapsingToolbarLayout collapsingToolbarLayout;
    AppBarLayout appBarLayout;
    View view;
    Snackbar snackbar;
    private AdView adView;
    private InterstitialAd interstitialAd;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_notif_detail);
        view = findViewById(android.R.id.content);

        if (Config.ENABLE_RTL_MODE) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
                getWindow().getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
            }
        }

        initAds();
        loadBannerAd();
        loadInterstitialAd();

        setupToolbar();
        databaseHandler = new DatabaseHandlerFavorite(getApplicationContext());
        floatingActionButton = (FloatingActionButton) findViewById(R.id.img_fav);

        Intent intent = getIntent();
        id = intent.getStringExtra("id");

        lyt_parent = findViewById(R.id.lyt_parent);
        lyt_content = findViewById(R.id.lyt_content);
        lyt_progress = findViewById(R.id.lyt_progress);

        channel_image = (ImageView) findViewById(R.id.channel_image);
        channel_name = (TextView) findViewById(R.id.channel_name);
        channel_category = (TextView) findViewById(R.id.channel_category);
        channel_description = (WebView) findViewById(R.id.channel_description);

        requestAction();
        addFavorite();

    }

    private void setupToolbar() {
        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        final ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setHomeButtonEnabled(true);
            getSupportActionBar().setTitle("");
        }

        appBarLayout = (AppBarLayout) findViewById(R.id.appbar);
        appBarLayout.setExpanded(true);
        collapsingToolbarLayout = (CollapsingToolbarLayout) findViewById(R.id.collapsing_toolbar);
        collapsingToolbarLayout.setTitle("");

        appBarLayout.addOnOffsetChangedListener(new AppBarLayout.OnOffsetChangedListener() {
            boolean isShow = false;
            int scrollRange = -1;

            @Override
            public void onOffsetChanged(AppBarLayout appBarLayout, int verticalOffset) {
                if (scrollRange == -1) {
                    scrollRange = appBarLayout.getTotalScrollRange();
                }
                if (scrollRange + verticalOffset == 0) {
                    collapsingToolbarLayout.setTitle(post.category_name);
                    isShow = true;
                } else if (isShow) {
                    collapsingToolbarLayout.setTitle("");
                    isShow = false;
                }
            }
        });

    }

    private void requestAction() {
        showFailedView(false, "");
        requestDetailsPostApi();
    }

    private void requestDetailsPostApi() {
        ApiInterface apiInterface = RestAdapter.createAPI();
        callbackCall = apiInterface.getPostDetail(id);
        callbackCall.enqueue(new Callback<CallbackChannelDetail>() {
            @Override
            public void onResponse(Call<CallbackChannelDetail> call, Response<CallbackChannelDetail> response) {
                CallbackChannelDetail resp = response.body();
                if (resp != null && resp.status.equals("ok")) {
                    post = resp.post;
                    if (Config.ENABLE_RTL_MODE) {
                        displayDataRTL();
                    } else {
                        displayData();
                    }
                } else {
                    onFailRequest();
                }
            }

            @Override
            public void onFailure(Call<CallbackChannelDetail> call, Throwable t) {
                if (!call.isCanceled()) onFailRequest();
            }

        });
    }

    private void onFailRequest() {
        if (NetworkCheck.isConnect(this)) {
            showFailedView(true, getString(R.string.failed_text));
        } else {
            showFailedView(true, getString(R.string.no_internet_text));
        }
    }

    public void displayData() {

        channel_name.setText(post.channel_name);
        channel_category.setText(post.category_name);

        if (post.channel_type != null && post.channel_type.equals("YOUTUBE")) {
            Picasso.with(this)
                    .load(Constant.YOUTUBE_IMG_FRONT + post.video_id + Constant.YOUTUBE_IMG_BACK)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);
        } else {

            Picasso.with(this)
                    .load(Config.ADMIN_PANEL_URL + "/upload/" + post.channel_image)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);

        }

        channel_image.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (NetworkCheck.isNetworkAvailable(ActivityOneSignalDetail.this)) {

                    if (post.channel_type != null && post.channel_type.equals("YOUTUBE")) {
                        Intent i = new Intent(ActivityOneSignalDetail.this, ActivityYoutubePlayer.class);
                        i.putExtra("id", post.video_id);
                        startActivity(i);
                    } else {
                        if (post.channel_url != null && post.channel_url.startsWith("rtmp://")) {
                            Intent intent = new Intent(ActivityOneSignalDetail.this, ActivityRtmpPlayer.class);
                            intent.putExtra("url", post.channel_url);
                            startActivity(intent);
                        } else {
                            Intent intent = new Intent(ActivityOneSignalDetail.this, ActivityStreamPlayer.class);
                            intent.putExtra("url", post.channel_url);
                            startActivity(intent);
                        }
                    }

                    showInterstitialAd();

                } else {
                    Toast.makeText(getApplicationContext(), getResources().getString(R.string.network_required), Toast.LENGTH_SHORT).show();
                }

            }
        });

        channel_description.setBackgroundColor(Color.parseColor("#ffffff"));
        channel_description.setFocusableInTouchMode(false);
        channel_description.setFocusable(false);
        channel_description.getSettings().setDefaultTextEncodingName("UTF-8");

        WebSettings webSettings = channel_description.getSettings();
        Resources res = getResources();
        int fontSize = res.getInteger(R.integer.font_size);
        webSettings.setDefaultFontSize(fontSize);

        String mimeType = "text/html; charset=UTF-8";
        String encoding = "utf-8";
        String htmlText = post.channel_description;

        String text = "<html><head>"
                + "<style type=\"text/css\">body{color: #525252;}"
                + "</style></head>"
                + "<body>"
                + htmlText
                + "</body></html>";

        channel_description.loadDataWithBaseURL(null, text, mimeType, encoding, null);
    }

    public void displayDataRTL() {

        channel_name.setText(post.channel_name);
        channel_category.setText(post.category_name);

        if (post.channel_type != null && post.channel_type.equals("YOUTUBE")) {
            Picasso.with(this)
                    .load(Constant.YOUTUBE_IMG_FRONT + post.video_id + Constant.YOUTUBE_IMG_BACK)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);
        } else {

            Picasso.with(this)
                    .load(Config.ADMIN_PANEL_URL + "/upload/" + post.channel_image)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);

        }

        channel_image.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (NetworkCheck.isNetworkAvailable(ActivityOneSignalDetail.this)) {

                    if (post.channel_type != null && post.channel_type.equals("YOUTUBE")) {
                        Intent i = new Intent(ActivityOneSignalDetail.this, ActivityYoutubePlayer.class);
                        i.putExtra("id", post.video_id);
                        startActivity(i);
                    } else {
                        if (post.channel_url != null && post.channel_url.startsWith("rtmp://")) {
                            Intent intent = new Intent(ActivityOneSignalDetail.this, ActivityRtmpPlayer.class);
                            intent.putExtra("url", post.channel_url);
                            startActivity(intent);
                        } else {
                            Intent intent = new Intent(ActivityOneSignalDetail.this, ActivityStreamPlayer.class);
                            intent.putExtra("url", post.channel_url);
                            startActivity(intent);
                        }
                    }

                    showInterstitialAd();

                } else {
                    Toast.makeText(getApplicationContext(), getResources().getString(R.string.network_required), Toast.LENGTH_SHORT).show();
                }

            }
        });

        channel_description.setBackgroundColor(Color.parseColor("#ffffff"));
        channel_description.setFocusableInTouchMode(false);
        channel_description.setFocusable(false);
        channel_description.getSettings().setDefaultTextEncodingName("UTF-8");

        WebSettings webSettings = channel_description.getSettings();
        Resources res = getResources();
        int fontSize = res.getInteger(R.integer.font_size);
        webSettings.setDefaultFontSize(fontSize);

        String mimeType = "text/html; charset=UTF-8";
        String encoding = "utf-8";
        String htmlText = post.channel_description;

        String text = "<html dir='rtl'><head>"
                + "<style type=\"text/css\">body{color: #525252;}"
                + "</style></head>"
                + "<body>"
                + htmlText
                + "</body></html>";

        channel_description.loadDataWithBaseURL(null, text, mimeType, encoding, null);
    }

    public void addFavorite() {

        List<Channel> data = databaseHandler.getFavRow(id);
        if (data.size() == 0) {
            floatingActionButton.setImageResource(R.drawable.ic_favorite_outline_white);
        } else {
            if (data.get(0).getChannel_id().equals(id)) {
                floatingActionButton.setImageResource(R.drawable.ic_favorite_white);
            }
        }

        floatingActionButton.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {

                List<Channel> data = databaseHandler.getFavRow(id);
                if (data.size() == 0) {
                    databaseHandler.AddtoFavorite(new Channel(
                            post.category_name,
                            post.channel_id,
                            post.channel_name,
                            post.channel_image,
                            post.channel_url,
                            post.channel_description,
                            post.channel_type,
                            post.video_id
                    ));
                    snackbar = Snackbar.make(view, getResources().getString(R.string.favorite_added), Snackbar.LENGTH_SHORT);
                    snackbar.show();

                    floatingActionButton.setImageResource(R.drawable.ic_favorite_white);

                } else {
                    if (data.get(0).getChannel_id().equals(id)) {
                        databaseHandler.RemoveFav(new Channel(id));
                        snackbar = Snackbar.make(view, getResources().getString(R.string.favorite_removed), Snackbar.LENGTH_SHORT);
                        snackbar.show();
                        floatingActionButton.setImageResource(R.drawable.ic_favorite_outline_white);
                    }
                }
            }
        });

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_detail, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {

            case android.R.id.home:
                onBackPressed();
                return true;

            case R.id.share:

                String news_heading = android.text.Html.fromHtml(getResources().getString(R.string.share_title) + " " + post.channel_name).toString();
                String share_text = android.text.Html.fromHtml(getResources().getString(R.string.share_content)).toString();
                Intent sendIntent = new Intent();
                sendIntent.setAction(Intent.ACTION_SEND);
                sendIntent.putExtra(Intent.EXTRA_TEXT, news_heading + "\n\n" + share_text + "\n\n" + "https://play.google.com/store/apps/details?id=" + getPackageName());
                sendIntent.setType("text/plain");
                startActivity(sendIntent);

                return true;

            default:
                return super.onOptionsItemSelected(menuItem);
        }
    }

    private void showFailedView(boolean show, String message) {
        View lyt_failed = findViewById(R.id.lyt_failed_home);
        ((TextView) findViewById(R.id.failed_message)).setText(message);
        if (show) {
            lyt_content.setVisibility(View.GONE);
            lyt_failed.setVisibility(View.VISIBLE);
            lyt_progress.setVisibility(View.GONE);
        } else {
            lyt_content.setVisibility(View.VISIBLE);
            lyt_failed.setVisibility(View.GONE);

            new Handler().postDelayed(new Runnable() {
                @Override
                public void run() {
                    lyt_progress.setVisibility(View.GONE);
                }
            }, 1500);
        }
        findViewById(R.id.failed_retry).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                requestAction();
            }
        });
    }

    @Override
    public void onBackPressed() {
        Intent intent = new Intent(getApplicationContext(), MainActivity.class);
        startActivity(intent);
        finish();
    }

    public void initAds() {
        if (Config.ENABLE_ADMOB_BANNER_ADS || Config.ENABLE_ADMOB_INTERSTITIAL_ADS_ON_PLAY_STREAMING) {
            MobileAds.initialize(ActivityOneSignalDetail.this, getResources().getString(R.string.admob_app_id));
        }
    }

    public void loadBannerAd() {
        if (Config.ENABLE_ADMOB_BANNER_ADS) {
            adView = (AdView) findViewById(R.id.adView);
            adView.loadAd(Tools.getAdRequest(ActivityOneSignalDetail.this));
            adView.setAdListener(new AdListener() {

                @Override
                public void onAdClosed() {
                }

                @Override
                public void onAdFailedToLoad(int error) {
                    adView.setVisibility(View.GONE);
                }

                @Override
                public void onAdLeftApplication() {
                }

                @Override
                public void onAdOpened() {
                }

                @Override
                public void onAdLoaded() {
                    adView.setVisibility(View.VISIBLE);
                }
            });

        } else {
            Log.d("AdMob", "AdMob Banner is Disabled");
        }
    }

    private void loadInterstitialAd() {
        if (Config.ENABLE_ADMOB_INTERSTITIAL_ADS_ON_PLAY_STREAMING) {
            interstitialAd = new InterstitialAd(getApplicationContext());
            interstitialAd.setAdUnitId(getResources().getString(R.string.admob_interstitial_unit_id));
            interstitialAd.loadAd(Tools.getAdRequest(ActivityOneSignalDetail.this));
            interstitialAd.setAdListener(new AdListener() {
                @Override
                public void onAdClosed() {
                    interstitialAd.loadAd(Tools.getAdRequest(ActivityOneSignalDetail.this));
                }
            });
        }
    }

    private void showInterstitialAd() {
        if (Config.ENABLE_ADMOB_INTERSTITIAL_ADS_ON_PLAY_STREAMING) {
            if (interstitialAd != null && interstitialAd.isLoaded()) {
                interstitialAd.show();
            }
        }
    }

}
