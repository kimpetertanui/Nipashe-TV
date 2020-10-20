package com.app.livetvstrean.activities;

import android.content.res.Resources;
import android.graphics.Color;
import android.os.Build;
import android.os.Bundle;
import android.os.Handler;
import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.ProgressBar;

import com.ads.mobitechadslib.AdsModel;
import com.ads.mobitechadslib.MobiAdBanner;
import com.ads.mobitechadslib.MobitechAds;
import com.app.livetvstrean.Config;
import com.app.livetvstrean.R;
import com.app.livetvstrean.models.Setting;
import com.app.livetvstrean.rests.ApiInterface;
import com.app.livetvstrean.rests.RestAdapter;

//import io.reactivex.disposables.CompositeDisposable;
import io.reactivex.disposables.CompositeDisposable;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ActivityPrivacyPolicy extends AppCompatActivity {

    WebView wv_privacy_policy;
    ProgressBar progressBar;
    Button btn_failed_retry;
    View lyt_failed;
    private AdsModel adsModel ;
    private MobiAdBanner mobiAdBanner ;
    private CompositeDisposable disposable = new CompositeDisposable();
    private String adCategory = "3" ; //specify the ad category you want to show.

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_privacy_policy);
        setupToolbar();

        if (Config.ENABLE_RTL_MODE) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
                getWindow().getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
            }
        }

        // ....................Interstitial Ad ...............
        MobitechAds. getIntertistialAd (
                ActivityPrivacyPolicy. this ,
                adCategory );
// ...................End of Interstitial ad............
//        // ----------------------Banner Ad --------------------.
//        mobiAdBanner = findViewById(R.id. bannerAd );
//        mobiAdBanner .setOnClickListener(v -> {
//            mobiAdBanner .viewBannerAd(MainActivity.this ,
//                    adsModel .getAd_urlandroid());
//        });
//        Timer timer = new Timer();
//        TimerTask myTask = new TimerTask() {
//            @Override
//            public void run() {
//                Observable<okhttp3.Response> observable =getBannerAd ( adCategory );
//                observable.subscribe(new Observer <Response> () {
//                    @Override
//                    public void onSubscribe(Disposable d) {
//                        disposable .add(d);
//                    }
//                    @Override
//                    public void onNext(Response response) {
//                        try {
//                            adsModel = getBannerAdValues (response.body().string());
//                            mobiAdBanner .showAd(getApplicationContext(),
//                                    adsModel .getAd_upload());
//                        } catch (IOException e) {
//                            e.printStackTrace();
//                        }
//                    }
//                    @Override
//                    public void onError(Throwable e) {
//                        Log. e ( "Banner" , "onError : " +e.getMessage() );
//                    }
//                    @Override
//                    public void onComplete() {
//                    }
//                });
//            }
//        };timer.schedule(myTask, 100 , 20000 ); //refresh after
////...............................end of banner ad ........................

        wv_privacy_policy = findViewById(R.id.webView);
        progressBar = findViewById(R.id.progressBar);
        btn_failed_retry = findViewById(R.id.failed_retry);
        lyt_failed = findViewById(R.id.lyt_failed);

        displayData();

        btn_failed_retry.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                lyt_failed.setVisibility(View.GONE);
                progressBar.setVisibility(View.VISIBLE);
                displayData();
            }
        });
    }

    public void setupToolbar() {
        final Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        final ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setHomeButtonEnabled(true);
            getSupportActionBar().setTitle(R.string.about_app_privacy_policy);
        }
    }

    public void displayData() {
        Handler handler = new Handler();
        handler.postDelayed(new Runnable() {
            @Override
            public void run() {
                loadData();
            }
        }, 1000);
    }

    public void loadData() {
        ApiInterface apiInterface = RestAdapter.createAPI();
        Call<Setting> call = apiInterface.getPrivacyPolicy();
        call.enqueue(new Callback<Setting>() {
            @Override
            public void onResponse(Call<Setting> call, Response<Setting> response) {
                String privacy_policy = response.body().getPrivacy_policy();
                try {

                    if (Config.ENABLE_RTL_MODE) {
                        wv_privacy_policy.setBackgroundColor(Color.parseColor("#ffffff"));
                        wv_privacy_policy.setFocusableInTouchMode(false);
                        wv_privacy_policy.setFocusable(false);
                        wv_privacy_policy.getSettings().setDefaultTextEncodingName("UTF-8");

                        WebSettings webSettings = wv_privacy_policy.getSettings();
                        Resources res = getResources();
                        int fontSize = res.getInteger(R.integer.font_size);
                        webSettings.setDefaultFontSize(fontSize);

                        String mimeType = "text/html; charset=UTF-8";
                        String encoding = "utf-8";
                        String htmlText = privacy_policy;

                        String text = "<html dir='rtl'><head>"
                                + "<style type=\"text/css\">body{color: #525252;}"
                                + "</style></head>"
                                + "<body>"
                                + htmlText
                                + "</body></html>";

                        wv_privacy_policy.loadDataWithBaseURL(null, text, mimeType, encoding, null);

                        progressBar.setVisibility(View.GONE);
                        lyt_failed.setVisibility(View.GONE);
                    } else {
                        wv_privacy_policy.setBackgroundColor(Color.parseColor("#ffffff"));
                        wv_privacy_policy.setFocusableInTouchMode(false);
                        wv_privacy_policy.setFocusable(false);
                        wv_privacy_policy.getSettings().setDefaultTextEncodingName("UTF-8");

                        WebSettings webSettings = wv_privacy_policy.getSettings();
                        Resources res = getResources();
                        int fontSize = res.getInteger(R.integer.font_size);
                        webSettings.setDefaultFontSize(fontSize);

                        String mimeType = "text/html; charset=UTF-8";
                        String encoding = "utf-8";
                        String htmlText = privacy_policy;

                        String text = "<html><head>"
                                + "<style type=\"text/css\">body{color: #525252;}"
                                + "</style></head>"
                                + "<body>"
                                + htmlText
                                + "</body></html>";

                        wv_privacy_policy.loadDataWithBaseURL(null, text, mimeType, encoding, null);

                        progressBar.setVisibility(View.GONE);
                        lyt_failed.setVisibility(View.GONE);
                    }

                } catch (Exception e) {
                    Log.d("onResponse", "There is an error");
                    e.printStackTrace();
                }
            }

            @Override
            public void onFailure(Call<Setting> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                lyt_failed.setVisibility(View.VISIBLE);
            }

        });
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {

            case android.R.id.home:
                onBackPressed();
                return true;

            default:
                return super.onOptionsItemSelected(menuItem);
        }
    }

}
