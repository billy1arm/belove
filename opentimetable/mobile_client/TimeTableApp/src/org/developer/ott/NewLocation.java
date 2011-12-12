package org.developer.ott;

import android.app.TabActivity;
import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.FrameLayout;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.TabHost;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.TabHost.OnTabChangeListener;


public class NewLocation extends TabActivity implements OnItemClickListener, OnTabChangeListener{
	public void onCreate(Bundle savedInstanceState) {
	    super.onCreate(savedInstanceState);
	    Bundle data = getIntent().getExtras();
	    setContentView(R.layout.location_new_main);  
	    res = getResources(); // Resource object to get Drawables
	    _tabHost = getTabHost();  // The activity TabHost

	    // Initialize a TabSpec for each tab and add it to the TabHost
	    setupTab(COUNTRY, data);
	    setupTab(CITY, data);
	    setupTab(CATEGORY, data);
	    setupTab(ORGANIZATION, data);
//	    
	    _tabHost.setCurrentTab(0);
	    _tabHost.setOnTabChangedListener(this);
	    	    
	}
	
	@Override
	public void onBackPressed() {
		Intent intent = new Intent();
	    setResult(RESULT_CANCELED, intent);
		super.onBackPressed();
	}

	private void setupTab(final String tag, Bundle data){
		int viewId = 0, arrayId = 0 ;
		String text = "";
		
		FrameLayout frameLayout = _tabHost.getTabContentView();
		if (COUNTRY == tag){
			viewId = R.id.content_country_listview;
			text = "Country:";
			arrayId = R.array.countries_array;
		} 
		else if (CITY == tag){
			viewId = R.id.content_city_listview;
			text = "City:";
			arrayId = R.array.cities_array;
		}
		else if (ORGANIZATION == tag){
			viewId = R.id.content_organization_listview;
			text = "Organization:";
			arrayId = R.array.organizations_array;
		}
		else if (CATEGORY == tag){
			viewId = R.id.content_category_listview;
			text = "Category:";
			arrayId = R.array.categories_array;
		}
		
	    TabHost.TabSpec spec = _tabHost.newTabSpec(tag)
		  .setIndicator(createTabView(
				  _tabHost.getContext(),
				  text,
				  data.getString(tag)))
		  .setContent(viewId);
	    _tabHost.addTab(spec);
		
		ListView lv  = (ListView)frameLayout.findViewById(viewId);
		
		lv.setAdapter((ListAdapter)new ArrayAdapter<String>(
				this, 
				android.R.layout.simple_list_item_1, 
				res.getStringArray(arrayId))
				);
		lv.setOnItemClickListener(this);
		lv.setTextFilterEnabled(true);
	}	
	
	@Override
	public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
			long arg3) {
		TextView tv = (TextView)arg1;
		String text = (String) tv.getText();
		String currentTab = _tabHost.getCurrentTabTag();
		View view = _tabHost.getCurrentTabView();
		
		if (currentTab == COUNTRY){
			_selectedCountry = text;
			_tabHost.setCurrentTab(1);
		} else
		if (currentTab == CITY){
			_selectedCity= text;
			_tabHost.setCurrentTab(2);
		} else
		if (currentTab == CATEGORY){
			_selectedCategory= text;
			_tabHost.setCurrentTab(3);
		} else
		if (currentTab == ORGANIZATION){
			_selectedOragnization= text;
			Intent intent = new Intent();
			intent.putExtra(COUNTRY, _selectedCountry);
			intent.putExtra(CITY, _selectedCity);
			intent.putExtra(CATEGORY, _selectedCategory);
			intent.putExtra(ORGANIZATION, _selectedOragnization);
		    setResult(RESULT_OK, intent);
			finish();
		}
		
		tv = (TextView)view.findViewById(android.R.id.content);
		tv.setText(text);
		/* TODO
		 * - make a request to the server here and in other Listners too
		 */
	}
		
	@Override
	public void onTabChanged(String tabId) {
		if ((tabId == ORGANIZATION)&&(_selectedCategory == "")){
			Toast.makeText(getApplicationContext(), "выберите категорию",
			          Toast.LENGTH_SHORT).show();

			_tabHost.setCurrentTab(2);
		}
		if ((tabId == CATEGORY)&&(_selectedCity == "")){
			Toast.makeText(getApplicationContext(), "выберите город",
			          Toast.LENGTH_SHORT).show();
			_tabHost.setCurrentTab(1);
		} 
		if ((tabId == CITY)&&(_selectedCountry == "")){
			Toast.makeText(getApplicationContext(), "выберите страну",
			          Toast.LENGTH_SHORT).show();
			_tabHost.setCurrentTab(0);
		}  
	}
	
	private View createTabView(final Context context, final String title,final String content) {
		View view = LayoutInflater.from(context).inflate(R.layout.location_new_tab_header_custom, null);
		TextView tv = (TextView) view.findViewById(android.R.id.title);
		tv.setText(title);
		tv = (TextView) view.findViewById(android.R.id.content);
		tv.setText(content);
		return view;
	}

	private String _selectedCountry = "";
	private String _selectedCity = "";
	private String _selectedCategory = "";
	private String _selectedOragnization = "";
	private TabHost _tabHost;
	
	private Resources res;
	
	public static final String COUNTRY = "country";
	public static final String CITY = "city";
	public static final String CATEGORY = "category";
	public static final String ORGANIZATION = "organization";
	
	
	
}
