package org.developer.ott;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.widget.TextView;
import android.widget.Toast;

public class TimeTableAppActivity extends Activity {
    private static final int LOCATION_REQUEST = 10;

	/** Called when the activity is first created. */
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
		_countryTV = (TextView)findViewById(R.id.country_textview);
		_cityTV = (TextView)findViewById(R.id.city_textview);
		_categoryTV = (TextView)findViewById(R.id.category_textview);
		_organizationTV = (TextView)findViewById(R.id.organization_textview);
		
        loadText();
    }

	@Override
	protected void onDestroy() {
		saveText();
		super.onDestroy();
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		MenuInflater inflater = getMenuInflater();
	    inflater.inflate(R.menu.main_menu, menu);
	    return true;
	}
    	
	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		switch (item.getItemId()){
			case R.id.change_location:
				ChangeLocation();
				return true;
			case R.id.show_timetables:
				ShowTimeTables();
				return true;
			default: 
				return false;
		}
	}

	void ChangeLocation(){
		Intent intent = new Intent(this, NewLocation.class);
		intent.putExtra(NewLocation.COUNTRY, _countryTV.getText());
		intent.putExtra(NewLocation.CITY, _cityTV.getText());
		intent.putExtra(NewLocation.CATEGORY, _categoryTV.getText());
		intent.putExtra(NewLocation.ORGANIZATION, _organizationTV.getText());
		startActivityForResult(intent, LOCATION_REQUEST);
	}
	
	void ShowTimeTables(){
		Intent intent = new Intent(this, Schedule.class); 
		startActivity(intent);
	}
	
	void saveText() {
		sPref = getPreferences(MODE_PRIVATE);
		Editor ed = sPref.edit();
		ed.putString(NewLocation.COUNTRY, _countryTV.getText().toString());
		ed.putString(NewLocation.CITY, _cityTV.getText().toString());
		ed.putString(NewLocation.CATEGORY, _categoryTV.getText().toString());
		ed.putString(NewLocation.ORGANIZATION, _organizationTV.getText().toString());
		ed.commit();
	}
	  
	void loadText() {
	    sPref = getPreferences(MODE_PRIVATE);
	    _countryTV.setText(sPref.getString(NewLocation.COUNTRY, ""));
	    _cityTV.setText(sPref.getString(NewLocation.CITY, ""));
	    _categoryTV.setText(sPref.getString(NewLocation.CATEGORY, ""));
	    _organizationTV.setText(sPref.getString(NewLocation.ORGANIZATION, ""));
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		Bundle extras = data.getExtras();
		if (resultCode == RESULT_OK)
			switch(requestCode) {
				case LOCATION_REQUEST:	  
					_countryTV.setText(extras.getString(NewLocation.COUNTRY));
					_cityTV.setText(extras.getString(NewLocation.CITY));
					_categoryTV.setText(extras.getString(NewLocation.CATEGORY));
					_organizationTV.setText(extras.getString(NewLocation.ORGANIZATION));
			    break;
			}
		else{
			Toast.makeText(getApplicationContext(), "Operanion cancelled", Toast.LENGTH_SHORT).show();
		}

	}
	
	private TextView _countryTV;
	private TextView _cityTV;
	private TextView _categoryTV;
	private TextView _organizationTV;
	private SharedPreferences sPref;
		
	
}