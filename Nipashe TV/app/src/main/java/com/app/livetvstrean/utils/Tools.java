package com.app.livetvstrean.utils;

import android.app.Activity;

import com.google.ads.mediation.admob.AdMobAdapter;
import com.google.android.gms.ads.AdRequest;

public class Tools {

    public static AdRequest getAdRequest(Activity activity) {
        return new AdRequest.Builder()
                .addNetworkExtrasBundle(AdMobAdapter.class, GDPR.getBundleAd(activity))
                .build();
    }

}
