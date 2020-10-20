package com.app.livetvstrean.activities;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import androidx.appcompat.app.AppCompatActivity;
import android.view.View;
import android.widget.ProgressBar;

import com.app.livetvstrean.Config;
import com.app.livetvstrean.R;
import com.app.livetvstrean.utils.Tools;
import com.google.android.gms.ads.AdListener;
import com.google.android.gms.ads.InterstitialAd;
import com.google.android.gms.ads.MobileAds;

public class ActivitySplash extends AppCompatActivity {

    Boolean isCancelled = false;
    private ProgressBar progressBar;
    private InterstitialAd interstitialAd;
    String id = "0", cname = "";

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);

        initAds();
        loadInterstitialAd();

        progressBar = findViewById(R.id.progressBar);
        progressBar.setVisibility(View.VISIBLE);

        if (getIntent().hasExtra("nid")) {
            id = getIntent().getStringExtra("nid");
            cname = getIntent().getStringExtra("cname");
        }

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                if (!isCancelled) {
                    if (id.equals("0")) {
                        Intent intent = new Intent(ActivitySplash.this, MainActivity.class);
                        startActivity(intent);
                        finish();
                    } else {
                        Intent intent = new Intent(ActivitySplash.this, ActivityOneSignalDetail.class);
                        intent.putExtra("id", id);
                        intent.putExtra("cname", cname);
                        startActivity(intent);
                        finish();
                    }
                }
                showInterstitialAd();
            }
        }, Config.SPLASH_TIME);

    }

    public void initAds() {
        if (Config.ENABLE_ADMOB_INTERSTITIAL_ADS_AFTER_SPLASH) {
            MobileAds.initialize(ActivitySplash.this, getResources().getString(R.string.admob_app_id));
        }
    }

    private void loadInterstitialAd() {
        if (Config.ENABLE_ADMOB_INTERSTITIAL_ADS_AFTER_SPLASH) {
            interstitialAd = new InterstitialAd(getApplicationContext());
            interstitialAd.setAdUnitId(getResources().getString(R.string.admob_interstitial_unit_id));
            interstitialAd.loadAd(Tools.getAdRequest(ActivitySplash.this));
            interstitialAd.setAdListener(new AdListener() {
                @Override
                public void onAdClosed() {
                    interstitialAd.loadAd(Tools.getAdRequest(ActivitySplash.this));
                }
            });
        }
    }

    private void showInterstitialAd() {
        if (Config.ENABLE_ADMOB_INTERSTITIAL_ADS_AFTER_SPLASH) {
            if (interstitialAd != null && interstitialAd.isLoaded()) {
                interstitialAd.show();
            }
        }
    }

}
