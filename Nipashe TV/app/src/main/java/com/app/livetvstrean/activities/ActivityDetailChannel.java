package com.app.livetvstrean.activities;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.res.Resources;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import com.google.android.material.appbar.AppBarLayout;
import com.google.android.material.appbar.CollapsingToolbarLayout;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.snackbar.Snackbar;
import androidx.localbroadcastmanager.content.LocalBroadcastManager;
import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import android.text.Html;
import android.util.Log;
import android.view.LayoutInflater;
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
import com.app.livetvstrean.databases.DatabaseHandlerFavorite;
import com.app.livetvstrean.fcm.NotificationUtils;
import com.app.livetvstrean.models.Channel;
import com.app.livetvstrean.utils.Constant;
import com.app.livetvstrean.utils.NetworkCheck;
import com.app.livetvstrean.utils.Tools;
import com.google.android.gms.ads.AdListener;
import com.google.android.gms.ads.AdView;
import com.google.android.gms.ads.InterstitialAd;
import com.google.android.gms.ads.MobileAds;
import com.squareup.picasso.Picasso;

import java.util.List;

public class ActivityDetailChannel extends AppCompatActivity {

    String str_category, str_id, str_image, str_name, str_url, str_description, str_channel_type, str_video_id;
    CollapsingToolbarLayout collapsingToolbarLayout;
    AppBarLayout appBarLayout;
    ImageView channel_image;
    TextView channel_name, channel_category;
    WebView channel_description;
    Snackbar snackbar;
    private AdView adView;
    View view;
    BroadcastReceiver broadcastReceiver;
    private InterstitialAd interstitialAd;
    DatabaseHandlerFavorite databaseHandler;
    FloatingActionButton floatingActionButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail);
        view = findViewById(android.R.id.content);

        if (Config.ENABLE_RTL_MODE) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
                getWindow().getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
            }
        }

        initAds();
        loadBannerAd();
        loadInterstitialAd();

        databaseHandler = new DatabaseHandlerFavorite(getApplicationContext());
        floatingActionButton = (FloatingActionButton) findViewById(R.id.img_fav);

        Intent intent = getIntent();
        if (null != intent) {
            str_category = intent.getStringExtra(Constant.KEY_CHANNEL_CATEGORY);
            str_id = intent.getStringExtra(Constant.KEY_CHANNEL_ID);
            str_name = intent.getStringExtra(Constant.KEY_CHANNEL_NAME);
            str_image = intent.getStringExtra(Constant.KEY_CHANNEL_IMAGE);
            str_url = intent.getStringExtra(Constant.KEY_CHANNEL_URL);
            str_description = intent.getStringExtra(Constant.KEY_CHANNEL_DESCRIPTION);
            str_channel_type = intent.getStringExtra(Constant.KEY_CHANNEL_TYPE);
            str_video_id = intent.getStringExtra(Constant.KEY_VIDEO_ID);
        }

        setupToolbar();

        channel_image = (ImageView) findViewById(R.id.channel_image);
        channel_name = (TextView) findViewById(R.id.channel_name);
        channel_category = (TextView) findViewById(R.id.channel_category);
        channel_description = (WebView) findViewById(R.id.channel_description);

        if (Config.ENABLE_RTL_MODE) {
            rtlLayout();
        } else {
            normalLayout();
        }

        addFavorite();

        broadcastReceiver = new BroadcastReceiver() {
            @Override
            public void onReceive(Context context, Intent intent) {
                // checking for type intent filter
                if (intent.getAction().equals(Constant.PUSH_NOTIFICATION)) {
                    // new push notification is received
                    final String id = intent.getStringExtra("id");
                    final String title = intent.getStringExtra("title");
                    final String message = intent.getStringExtra("message");
                    final String image_url = intent.getStringExtra("image_url");

                    LayoutInflater layoutInflaterAndroid = LayoutInflater.from(ActivityDetailChannel.this);
                    View mView = layoutInflaterAndroid.inflate(R.layout.custom_dialog, null);

                    final AlertDialog.Builder alert = new AlertDialog.Builder(ActivityDetailChannel.this);
                    alert.setView(mView);

                    final TextView notification_title = mView.findViewById(R.id.title);
                    final TextView notification_message = mView.findViewById(R.id.message);
                    final ImageView notification_image = mView.findViewById(R.id.big_image);

                    if (id != null) {
                        if (id.equals("0")) {
                            notification_title.setText(title);
                            notification_message.setText(Html.fromHtml(message));
                            Picasso.with(ActivityDetailChannel.this)
                                    .load(image_url.replace(" ", "%20"))
                                    .placeholder(R.drawable.ic_thumbnail)
                                    .into(notification_image);
                            alert.setPositiveButton(getResources().getString(R.string.option_ok), null);
                        } else {
                            notification_title.setText(title);
                            notification_message.setText(Html.fromHtml(message));
                            Picasso.with(ActivityDetailChannel.this)
                                    .load(image_url.replace(" ", "%20"))
                                    .placeholder(R.drawable.ic_thumbnail)
                                    .into(notification_image);

                            alert.setPositiveButton(getResources().getString(R.string.option_read_more), new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    Intent intent = new Intent(getApplicationContext(), ActivityFCMDetail.class);
                                    intent.putExtra("id", id);
                                    startActivity(intent);
                                }
                            });
                            alert.setNegativeButton(getResources().getString(R.string.option_dismis), null);
                        }
                        alert.setCancelable(false);
                        alert.show();
                    }

                }
            }
        };

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
                    collapsingToolbarLayout.setTitle(str_category);
                    isShow = true;
                } else if (isShow) {
                    collapsingToolbarLayout.setTitle("");
                    isShow = false;
                }
            }
        });

    }

    public void normalLayout() {

        channel_name.setText(str_name);
        channel_category.setText(str_category);

        if (str_channel_type != null && str_channel_type.equals("YOUTUBE")) {
            Picasso.with(this)
                    .load(Constant.YOUTUBE_IMG_FRONT + str_video_id + Constant.YOUTUBE_IMG_BACK)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);
        } else {

            Picasso.with(this)
                    .load(Config.ADMIN_PANEL_URL + "/upload/" + str_image)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);

        }

        channel_image.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (NetworkCheck.isNetworkAvailable(ActivityDetailChannel.this)) {

                    if (str_channel_type != null && str_channel_type.equals("YOUTUBE")) {
                        Intent i = new Intent(ActivityDetailChannel.this, ActivityYoutubePlayer.class);
                        i.putExtra("id", str_video_id);
                        startActivity(i);
                    } else {
                        if (str_url != null && str_url.startsWith("rtmp://")) {
                            Intent intent = new Intent(ActivityDetailChannel.this, ActivityRtmpPlayer.class);
                            intent.putExtra("url", str_url);
                            startActivity(intent);
                        } else {
                            Intent intent = new Intent(ActivityDetailChannel.this, ActivityStreamPlayer.class);
                            intent.putExtra("url", str_url);
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
        String htmlText = str_description;

        String text = "<html><head>"
                + "<style type=\"text/css\">body{color: #525252;}"
                + "</style></head>"
                + "<body>"
                + htmlText
                + "</body></html>";

        channel_description.loadDataWithBaseURL(null, text, mimeType, encoding, null);
    }

    public void rtlLayout() {

        channel_name.setText(str_name);
        channel_category.setText(str_category);

        if (str_channel_type != null && str_channel_type.equals("YOUTUBE")) {
            Picasso.with(this)
                    .load(Constant.YOUTUBE_IMG_FRONT + str_video_id + Constant.YOUTUBE_IMG_BACK)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);
        } else {

            Picasso.with(this)
                    .load(Config.ADMIN_PANEL_URL + "/upload/" + str_image)
                    .placeholder(R.drawable.ic_thumbnail)
                    .into(channel_image);

        }
        channel_image.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (NetworkCheck.isNetworkAvailable(ActivityDetailChannel.this)) {

                    if (str_channel_type != null && str_channel_type.equals("YOUTUBE")) {
                        Intent i = new Intent(ActivityDetailChannel.this, ActivityYoutubePlayer.class);
                        i.putExtra("id", str_video_id);
                        startActivity(i);
                    } else {
                        if (str_url != null && str_url.startsWith("rtmp://")) {
                            Intent intent = new Intent(ActivityDetailChannel.this, ActivityRtmpPlayer.class);
                            intent.putExtra("url", str_url);
                            startActivity(intent);
                        } else {
                            Intent intent = new Intent(ActivityDetailChannel.this, ActivityStreamPlayer.class);
                            intent.putExtra("url", str_url);
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
        String htmlText = str_description;

        String text = "<html dir='rtl'><head>"
                + "<style type=\"text/css\">body{color: #525252;}"
                + "</style></head>"
                + "<body>"
                + htmlText
                + "</body></html>";

        channel_description.loadDataWithBaseURL(null, text, mimeType, encoding, null);
    }

    public void addFavorite() {

        List<Channel> data = databaseHandler.getFavRow(str_id);
        if (data.size() == 0) {
            floatingActionButton.setImageResource(R.drawable.ic_favorite_outline_white);
        } else {
            if (data.get(0).getChannel_id().equals(str_id)) {
                floatingActionButton.setImageResource(R.drawable.ic_favorite_white);
            }
        }

        floatingActionButton.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {

                List<Channel> data = databaseHandler.getFavRow(str_id);
                if (data.size() == 0) {
                    databaseHandler.AddtoFavorite(new Channel(
                            str_category,
                            str_id,
                            str_name,
                            str_image,
                            str_url,
                            str_description,
                            str_channel_type,
                            str_video_id
                    ));
                    snackbar = Snackbar.make(view, getResources().getString(R.string.favorite_added), Snackbar.LENGTH_SHORT);
                    snackbar.show();

                    floatingActionButton.setImageResource(R.drawable.ic_favorite_white);

                } else {
                    if (data.get(0).getChannel_id().equals(str_id)) {
                        databaseHandler.RemoveFav(new Channel(str_id));
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

                String news_heading = android.text.Html.fromHtml(getResources().getString(R.string.share_title) + " " + str_name).toString();
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

    public void initAds() {
        if (Config.ENABLE_ADMOB_BANNER_ADS || Config.ENABLE_ADMOB_INTERSTITIAL_ADS_ON_PLAY_STREAMING) {
            MobileAds.initialize(ActivityDetailChannel.this, getResources().getString(R.string.admob_app_id));
        }
    }

    public void loadBannerAd() {
        if (Config.ENABLE_ADMOB_BANNER_ADS) {
            adView = (AdView) findViewById(R.id.adView);
            adView.loadAd(Tools.getAdRequest(ActivityDetailChannel.this));
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
            interstitialAd.loadAd(Tools.getAdRequest(ActivityDetailChannel.this));
            interstitialAd.setAdListener(new AdListener() {
                @Override
                public void onAdClosed() {
                    interstitialAd.loadAd(Tools.getAdRequest(ActivityDetailChannel.this));
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

    @Override
    protected void onResume() {
        super.onResume();
        LocalBroadcastManager.getInstance(this).registerReceiver(broadcastReceiver, new IntentFilter(Constant.REGISTRATION_COMPLETE));
        LocalBroadcastManager.getInstance(this).registerReceiver(broadcastReceiver, new IntentFilter(Constant.PUSH_NOTIFICATION));
        NotificationUtils.clearNotifications(getApplicationContext());
    }

    @Override
    protected void onPause() {
        LocalBroadcastManager.getInstance(this).unregisterReceiver(broadcastReceiver);
        super.onPause();
    }

}
