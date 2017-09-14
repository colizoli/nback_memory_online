contains = function(a, array)
 {
	var i = array.length;
	while(i--)
	{
		if(array[i] == a)
		return true;
	}
	return false;
 }
