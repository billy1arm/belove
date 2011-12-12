package org.developer.ott;

import android.app.ListActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.ListView;

public class Schedule extends ListActivity  {
	
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
		String[] values = new String[] { "Android", "iPhone", "WindowsMobile",
				"Blackberry", "WebOS", "Ubuntu", "Windows7", "Max OS X",
				"Linux", "OS/2" };
		// Use your own layout
		
		View header = getLayoutInflater().inflate(R.layout.schedule_header, null);
		ListView listView = getListView();
		listView.addHeaderView(header);
		ArrayAdapter<String> adapter = new ArrayAdapter<String>(this,
				R.layout.schedule_listitem, R.id.label, values);
		setListAdapter(adapter);
		//getListView().addHeaderView((View)findViewById(R.layout.schedule_header));
    }
    
}
