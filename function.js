
function ajaxRequest() {
	var ajaxRequest;
	try{
	// Opera 8.0+, Firefox, Safari
	ajaxRequest = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			ajaxRequest = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				ajaxRequest = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
	return ajaxRequest;
}

/* alert if user successfully followed another user*/
function follow_alert(user_id, following_id) {
	var ajax = ajaxRequest();
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4) {
			window.location.reload(true);
		};
	};
	ajax.open('GET', 'function.php?function=follow&user_id='+user_id+'&following_id='+following_id, true);
	ajax.send(null);
};

/* alert if user successfully  unfollowed another user */
function unfollow_alert(user_id, following_id) {
	var ajax = ajaxRequest();
	ajax.onreadystatechange = function() {
		if (ajax.readyState === 4) {
			window.location.reload(true);
		}
	}

	ajax.open('GET', 'function.php?function=unfollow&user_id='+user_id+'&following_id='+following_id, true);
	ajax.send(null);
}