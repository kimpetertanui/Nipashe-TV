package com.app.livetvstrean.activities;

import android.app.Application;

import com.onesignal.OneSignal;

public class MyApplication extends Application {

    public static final String TAG = MyApplication.class.getSimpleName();
    private static MyApplication mInstance;

    @Override
    public void onCreate() {
        super.onCreate();
        mInstance = this;
        OneSignal.startInit(this)
                .unsubscribeWhenNotificationsAreDisabled(true)
                .init();

    }

}
