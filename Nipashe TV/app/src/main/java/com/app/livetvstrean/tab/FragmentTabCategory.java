package com.app.livetvstrean.tab;

import android.content.Context;
import android.os.Bundle;
import androidx.annotation.Nullable;
import com.google.android.material.appbar.AppBarLayout;
import androidx.coordinatorlayout.widget.CoordinatorLayout;
import com.google.android.material.tabs.TabLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentPagerAdapter;
import androidx.viewpager.widget.ViewPager;
import androidx.appcompat.widget.Toolbar;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.app.livetvstrean.Config;
import com.app.livetvstrean.R;
import com.app.livetvstrean.activities.MainActivity;
import com.app.livetvstrean.fragments.FragmentCategory;
import com.app.livetvstrean.fragments.FragmentRecent;
import com.app.livetvstrean.utils.AppBarLayoutBehavior;

public class FragmentTabCategory extends Fragment {

    private MainActivity mainActivity;
    private Toolbar toolbar;
    public static TabLayout tabLayout;
    public static ViewPager viewPager;

    public static int single_tab = 1;
    public static int double_tab = 2;

    public FragmentTabCategory() {
        // Required empty public constructor
    }

    @Override
    public void onAttach(Context activity) {
        super.onAttach(activity);
        mainActivity = (MainActivity) activity;
    }

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.tab_layout, container, false);

        AppBarLayout appBarLayout = view.findViewById(R.id.tab_appbar_layout);
        ((CoordinatorLayout.LayoutParams) appBarLayout.getLayoutParams()).setBehavior(new AppBarLayoutBehavior());

        tabLayout = (TabLayout) view.findViewById(R.id.tabs);
        viewPager = (ViewPager) view.findViewById(R.id.viewpager);

        toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        setupToolbar();

        viewPager.setAdapter(new MyAdapter(getChildFragmentManager()));
        viewPager.setCurrentItem(1);

        if (Config.ENABLE_TAB_LAYOUT) {
            tabLayout.post(new Runnable() {
                @Override
                public void run() {
                    tabLayout.setupWithViewPager(viewPager);
                }
            });
        } else {
            tabLayout.setVisibility(View.GONE);
        }

        return view;

    }

    class MyAdapter extends FragmentPagerAdapter {

        public MyAdapter(FragmentManager fm) {
            super(fm);
        }

        @Override
        public Fragment getItem(int position) {

            if (Config.ENABLE_TAB_LAYOUT) {
                switch (position) {
                    case 0:
                        return new FragmentRecent();
                    case 1:
                        return new FragmentCategory();
                }
            } else {
                switch (position) {
                    case 0:
                        return new FragmentCategory();
                }
            }
            return null;
        }

        @Override
        public int getCount() {

            if (Config.ENABLE_TAB_LAYOUT) {
                return double_tab;
            } else {
                return single_tab;
            }

        }

        @Override
        public CharSequence getPageTitle(int position) {

            if (Config.ENABLE_TAB_LAYOUT) {
                switch (position) {
                    case 0:
                        return getResources().getString(R.string.tab_recent);
                    case 1:
                        return getResources().getString(R.string.tab_category);
                }
            } else {
                switch (position) {
                    case 0:
                        return getResources().getString(R.string.tab_category);
                }
            }

            return null;
        }
    }

    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        mainActivity.setupNavigationDrawer(toolbar);
    }

    private void setupToolbar() {
        toolbar.setTitle(getString(R.string.app_name));
        if (Config.ENABLE_TAB_LAYOUT) {
            Log.d("Log", "Tab Layout is Enabled");
        } else {
            toolbar.setSubtitle(getString(R.string.tab_category));
        }
            mainActivity.setSupportActionBar(toolbar);
    }

}

