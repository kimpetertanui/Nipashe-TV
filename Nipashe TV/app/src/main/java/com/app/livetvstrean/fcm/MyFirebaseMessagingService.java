package com.app.livetvstrean.fcm;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.drawable.Drawable;
import android.media.RingtoneManager;
import android.net.Uri;
import android.preference.PreferenceManager;
import androidx.annotation.NonNull;
import androidx.core.app.NotificationCompat;
import androidx.localbroadcastmanager.content.LocalBroadcastManager;
import android.text.Html;
import android.util.Log;

import com.app.livetvstrean.R;
import com.app.livetvstrean.activities.MainActivity;
import com.app.livetvstrean.utils.Constant;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

public class MyFirebaseMessagingService extends FirebaseMessagingService {

    private static final String TAG = "MyFirebaseMsgService";
    private SharedPreferences preferences;

    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {

        preferences = PreferenceManager.getDefaultSharedPreferences(getBaseContext());

        //Displaying data in log
        //It is optional
        Log.d(TAG, "From: " + remoteMessage.getFrom());
        Log.d(TAG, "Notification Message Body: " + remoteMessage.getData().get("title"));
        Log.d(TAG, "Notification Message Link: " + remoteMessage.getData().get("link"));
        Log.d(TAG, "Notification Message Image: " + remoteMessage.getData().get("image"));

        String image_url = remoteMessage.getData().get("image");
        Bitmap bitmap = getBitmapfromUrl(image_url);

        String id = remoteMessage.getData().get("id");
        String title = remoteMessage.getData().get("title");
        String message = remoteMessage.getData().get("message");
        String link = remoteMessage.getData().get("link");

        if (!NotificationUtils.isAppIsInBackground(getApplicationContext())) {

            Intent pushNotification = new Intent(Constant.PUSH_NOTIFICATION);
            pushNotification.putExtra("id", id);
            pushNotification.putExtra("title", title);
            pushNotification.putExtra("message", message);
            pushNotification.putExtra("image_url", image_url);
            LocalBroadcastManager.getInstance(this).sendBroadcast(pushNotification);

            NotificationUtils notificationUtils = new NotificationUtils(getApplicationContext());
            notificationUtils.playNotificationSound();

        } else {
            //Calling method to generate notification
            sendNotification(id, title, message, link, bitmap);
        }

    }

    //This method is only generating push notification
    //It is same as we did in earlier posts
    private void sendNotification(String id, String title, String message, String link, Bitmap bitmap) {

        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);

        if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.O) {

            NotificationCompat.Builder builder;

            NotificationCompat.BigPictureStyle notiStyle = new NotificationCompat.BigPictureStyle();
            notiStyle.bigPicture(bitmap);
            notiStyle.setBigContentTitle(title);
            notiStyle.setSummaryText(Html.fromHtml(message, Html.FROM_HTML_MODE_LEGACY));

            Intent intent = new Intent(getApplicationContext(), MainActivity.class);
            intent.putExtra("id", id);
            intent.putExtra("link", link);
            intent.addFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP | Intent.FLAG_ACTIVITY_CLEAR_TOP);
            PendingIntent pendingIntent = PendingIntent.getActivity(getApplicationContext(), 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);

            NotificationChannel notificationChannel = new NotificationChannel("my_channel_id_001", "My Notifications", NotificationManager.IMPORTANCE_DEFAULT);
            notificationManager.createNotificationChannel(notificationChannel);
            builder = new NotificationCompat.Builder(getBaseContext(), notificationChannel.getId());

            builder.setContentTitle(title)
                    .setLargeIcon(BitmapFactory.decodeResource(getResources(), R.drawable.ic_notification_large_icon))
                    .setSmallIcon(R.drawable.ic_stat_onesignal_default)
                    .setContentText(Html.fromHtml(message, Html.FROM_HTML_MODE_LEGACY))
                    .setStyle(notiStyle)
                    .setDefaults(Notification.DEFAULT_ALL)
                    .setAutoCancel(true)
                    .setBadgeIconType(R.mipmap.ic_launcher)
                    .setContentIntent(pendingIntent)
                    .setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION));

            Notification notification = builder.build();
            notificationManager.notify(0, notification);

        } else {

            NotificationCompat.BigPictureStyle notiStyle = new NotificationCompat.BigPictureStyle();
            notiStyle.bigPicture(bitmap);
            notiStyle.setBigContentTitle(title);
            notiStyle.setSummaryText(Html.fromHtml(message));

            Intent intent = new Intent(getApplicationContext(), MainActivity.class);
            intent.putExtra("id", id);
            intent.putExtra("link", link);
            intent.addFlags(Intent.FLAG_ACTIVITY_SINGLE_TOP | Intent.FLAG_ACTIVITY_CLEAR_TOP);
            PendingIntent pendingIntent = PendingIntent.getActivity(getApplicationContext(), 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);

            NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this)
                    .setLargeIcon(BitmapFactory.decodeResource(getResources(), R.drawable.ic_notification_large_icon))
                    .setSmallIcon(R.drawable.ic_stat_onesignal_default)
                    .setContentTitle(title)
                    .setContentText(Html.fromHtml(message))
                    .setStyle(notiStyle)
                    .setAutoCancel(true);

            if (preferences.getBoolean("notifications_new_message_vibrate", true)) {
                notificationBuilder.setVibrate(new long[]{1000, 1000, 1000, 1000, 1000});
            }
            if (preferences.getString("notifications_new_message_ringtone", null) != null) {
                notificationBuilder.setSound(Uri.parse(preferences.getString("notifications_new_message_ringtone", null)));
            } else {
                Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
                notificationBuilder.setSound(alarmSound);
            }
            if (preferences.getBoolean("notifications_new_message", true)) {
                notificationBuilder.setContentIntent(pendingIntent);
                notificationManager.notify(1, notificationBuilder.build());
            }

        }

    }

    public Bitmap getBitmapfromUrl(String imageUrl) {
        try {
            URL url = new URL(imageUrl);
            HttpURLConnection connection = (HttpURLConnection) url.openConnection();
            connection.setDoInput(true);
            connection.connect();
            InputStream input = connection.getInputStream();
            Bitmap bitmap = BitmapFactory.decodeStream(input);
            return bitmap;

        } catch (Exception e) {
            e.printStackTrace();
            return null;

        }
    }

    @NonNull
    private Bitmap getBitmapFromDrawable(@NonNull Drawable drawable) {
        final Bitmap bmp = Bitmap.createBitmap(drawable.getIntrinsicWidth(), drawable.getIntrinsicHeight(), Bitmap.Config.ARGB_8888);
        final Canvas canvas = new Canvas(bmp);
        drawable.setBounds(0, 0, canvas.getWidth(), canvas.getHeight());
        drawable.draw(canvas);
        return bmp;
    }

}