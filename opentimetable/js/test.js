var testTime = [
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
];

var testTime_not7 = [
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
];

var testTime_more7 = [
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},

];

var testTime_notOK = [
	{start: '09:00:00', end: '20:00:00',  lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},
	{start: '09:00:00', end: '20:00:00', lunch_start: '06:15:00', lunch_end: '06:30:00'},

];

test('convertTime()', function() {
	ok(convertTime(testTime), 'Все ок время распознано');
	ok(!convertTime(testTime_not7), '6 days check');
	ok(!convertTime(testTime_more7), '8 days check');
	ok(!convertTime(testTime_notOK), 'not full filling')
})

test('trim()', function () {
  equals(trim(''), '', 'Пустая строка');
  ok(trim('   ') === '', 'Строка из пробельных символов');
  same(trim(), '', 'Без параметра');
  equals(trim(' x'), 'x', 'Начальные пробелы');
  equals(trim('x '), 'x', 'Концевые пробелы');
  equals(trim(' x '), 'x', 'Пробелы с обоих концов');
  equals(trim('    x  '), 'x', 'Табы');
  equals(trim('    x   y  '), 'x   y', 'Табы и пробелы внутри строки не трогаем');

});

 test('cutText()', function() {
	equals(cutText('russian_federation'), 'russian_federat...', 'норма обрезания текста');
	equals(cutText(''), '', 'пустая строка');
	equals(cutText('malo'), 'malo', 'мало букв');
	equals(cutText('metro-club'), 'metro-club', 'есть тире');
	equals(cutText('	metro-club metro-clubmetro-club'), 'metro-club metr...', 'табы+ много букв');
}) 



