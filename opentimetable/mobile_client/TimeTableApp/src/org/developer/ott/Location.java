package org.developer.ott;

import java.util.ArrayList;
import java.util.List;

import android.app.ListActivity;
import android.content.ContentValues;
import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;

public class Location extends ListActivity implements OnItemClickListener{
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		_dbHelper = new LocationDBHelper(this);
		reloadDatabase();

		ListView lv = getListView();
		lv.setTextFilterEnabled(true);

		lv.setOnItemClickListener(this);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		//MenuInflater inflater = getMenuInflater();
		//inflater.inflate(R.menu.main_menu, menu);
		menu.add(Menu.NONE, ACTION_ADD_RECORD, Menu.NONE, "add record");
		menu.add(Menu.NONE, ACTION_CLEAR_TABLE, Menu.NONE, "drop table");
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		switch (item.getItemId()){
			case ACTION_ADD_RECORD:
				addNewLocation();
				return true;
			case ACTION_CLEAR_TABLE:
				clearDatabese();
				reloadDatabase();
				return true;
			default: 
				return false;
		}
	}
	
	private void addNewLocation(){
		Intent intent = new Intent(this, NewLocation.class);
		// TODO: 
		// VERY VERY VERY BAG CODING!!! REMOVE IT NOW!!!
		intent.putExtra(NewLocation.COUNTRY, "");
		intent.putExtra(NewLocation.CITY, "");
		intent.putExtra(NewLocation.CATEGORY, "");
		intent.putExtra(NewLocation.ORGANIZATION, "");
		startActivityForResult(intent, ACTION_ADD_RECORD);
	}
	
	private void clearDatabese(){
		_dbHelper.dropTable(LocationDBHelper.LOCATION_TABLE_NAME);
	}
	
	private void reloadDatabase(){
		List<String> list = new ArrayList<String>();
		//SQLiteDatabase db = dbHelper.getWritableDatabase();
		Cursor cr = _dbHelper.getTable(LocationDBHelper.LOCATION_TABLE_NAME);
		if (cr.moveToFirst()){
			//int idColIndex = c.getColumnIndex("id");
			int countryColIndex = cr.getColumnIndex(LocationDBHelper.KEY_COUNTRY);
	        int cityColIndex = cr.getColumnIndex(LocationDBHelper.KEY_CITY);
	        int categoryColIndex = cr.getColumnIndex(LocationDBHelper.KEY_CATEGORY);
	        int organizationColIndex = cr.getColumnIndex(LocationDBHelper.KEY_ORGANIZATION);	        
			do {
				// TODO: Make this returned data useful
				cr.getString(countryColIndex);
				cr.getString(cityColIndex);
				cr.getString(categoryColIndex);
				list.add(cr.getString(organizationColIndex));
			} while (cr.moveToNext());		
		};
		setListAdapter(new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, list));
		//db.close();
	}
	    
	public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
		// When clicked, show a toast with the TextView text
		Toast	.makeText(	getApplicationContext(),
							((TextView) view).getText(),
							Toast.LENGTH_SHORT)
				.show();
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		Bundle extras = data.getExtras();
		ContentValues cv = new ContentValues();
		if (resultCode == RESULT_OK){
			switch (requestCode){
				case ACTION_ADD_RECORD:	
					cv.put(LocationDBHelper.KEY_COUNTRY, extras.getString(NewLocation.COUNTRY));
					cv.put(LocationDBHelper.KEY_CITY, extras.getString(NewLocation.CITY));
					cv.put(LocationDBHelper.KEY_CATEGORY, extras.getString(NewLocation.CATEGORY));
					cv.put(LocationDBHelper.KEY_ORGANIZATION, extras.getString(NewLocation.ORGANIZATION));
					_dbHelper.addRecord(LocationDBHelper.LOCATION_TABLE_NAME, cv);
					Toast	.makeText(	getApplicationContext(),
										"Adding record to database",
										Toast.LENGTH_SHORT)
							.show();
					reloadDatabase();
					break;
				default:
					break;
			}
		}
		else{
			Toast.makeText(getApplicationContext(), "Operanion cancelled", Toast.LENGTH_SHORT).show();
		}
		
		super.onActivityResult(requestCode, resultCode, data);
	}



	@Override
	protected void onDestroy() {
		// TODO Auto-generated method stub
		_dbHelper.close();
		super.onDestroy();
	}

	private LocationDBHelper _dbHelper;
	
	private final int ACTION_ADD_RECORD = 1000;
	private final int ACTION_CLEAR_TABLE = 1001;
	
}

