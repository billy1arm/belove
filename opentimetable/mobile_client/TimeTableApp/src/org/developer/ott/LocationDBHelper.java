package org.developer.ott;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class LocationDBHelper extends SQLiteOpenHelper {
	
	public LocationDBHelper(Context context) {
		super(context, DATABASE_NAME, null, DATABASE_VERSION);
		// TODO Auto-generated constructor stub
	}
	
	@Override
	public void onCreate(SQLiteDatabase db) {
		// TODO Auto-generated method stub
		db.execSQL(LOCATION_TABLE_CREATE);
	}
	
//	@Override
//	public synchronized void close() {
//		// TODO Auto-generated method stub
//		super.close();
//	}
//
//	@Override
//	public void onOpen(SQLiteDatabase db) {
//		// TODO Auto-generated method stub
//		super.onOpen(db);
//	}

	@Override
	public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
		// TODO Auto-generated method stub		
	}
	
	public long dropTable(SQLiteDatabase db, String table){
		return db.delete(table, null, null);
	}
	
	public long addRecord(SQLiteDatabase db, String table, ContentValues cv){
		return db.insert(table, null, cv);
	}
	
	public Cursor getTable(SQLiteDatabase db, String table){
		return db.query(table, null, null, null, null, null, null); 
	}
	
	private static final int DATABASE_VERSION = 1;
	private static final String DATABASE_NAME = "opentimetable";
	private static final String LOCATION_TABLE_NAME = "location";
	private static final String KEY_ID = "_id";
	private static final String KEY_COUNTRY = "country";
	private static final String KEY_CITY = "city";
	private static final String KEY_CATEGORY = "category";
	private static final String KEY_ORGANIZATION = "organization";
	private static final String LOCATION_TABLE_CREATE =
		"CREATE TABLE " + LOCATION_TABLE_NAME + " (" 
		+ KEY_ID + " integer primary key autoincrement, "
		+ KEY_COUNTRY + " text not null,"
        + KEY_CITY + " text not null,"
        + KEY_CATEGORY + " text not null,"
        + KEY_ORGANIZATION + " text not null,"
        + ");" ;
	

}
