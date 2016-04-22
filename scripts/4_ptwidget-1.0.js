
	var ispace_txtElements = ['searchfield'];
    var ispace_brand = '&lt;cobrand&gt;';

function loadScriptTag(fullUrl) {

    // Build a script Id with a timestamp and a random number
	var scriptId = "JscriptId" + (new Date()).getTime() + Math.floor(Math.random()*11);
	
	var scriptObj = document.createElement("script");
	scriptObj.setAttribute("type", "text/javascript");
	scriptObj.setAttribute("charset", "utf-8");
	scriptObj.setAttribute("src", fullUrl + "&reqID=" + scriptId);
	scriptObj.setAttribute("id", scriptId);
	
	document.getElementsByTagName("head").item(0).appendChild(scriptObj);	
}
 
function removeScriptTagById(id) {
    var jScriptTag = document.getElementById(id);
    jScriptTag.parentNode.removeChild(jScriptTag);
} 
 
function ISuggest()
{
	//RFCID for search suggest
	var _RFCID = '114';
	
	//original RFCID
	var _originalRFCID = null;
	
	//holds reference to RFCID element
	var _rfcidElements = null;
	
	//holds the xmlHttpRequest object
	var _requestClient = null;
	
	//holds reference to textbox for which suggestions are requested
	var _trackingElement = null;
	
	//text box which has current focus
	var _activeElement = null;
	
	//the query which was last requested and being displayed
	var _activeQuery = new function(){
		this.query = null;
		this.response = null;
	};
	
	//time to wait before creating request.
	var _timeoutPeriod = 500;
	
	//index of current selected suggestion
	var _currentSelection = -1;
	
	//selected suggestion
	var _selectedValue = null;
	
	//reference to div used for display
	var _suggestionsPanel = null;
	
	var _jsonClient = null;
	
	//get the absolute position of an object 
	var _findPos = function ( obj ) {
		var curleft = curtop = 0;
		try{
			if (obj.offsetParent) {
				do {
					curleft += obj.offsetLeft;
					curtop += obj.offsetTop;
				} while (obj = obj.offsetParent);
				
				return {left:curleft, top:curtop};
			}
		}
		catch(e){}
		return null;
	};
	
	var _changeCallbacks = {};
 
	//selects previous suggestion
	var _selectPrevious = function(){
		if(_currentSelection >= 0)
		_suggestionsPanel.childNodes[_currentSelection].className = 'suggest_link';
		
		if(_currentSelection == 0 || _currentSelection == -1)
			_currentSelection = _suggestionsPanel.childNodes.length - 1;
		else
			_currentSelection--;
		_suggestionsPanel.childNodes[_currentSelection].className = 'suggest_link_over';
		
		if(_currentSelection > -1){
			var value = _suggestionsPanel.childNodes[_currentSelection].childNodes[0].nodeValue;
			_activeQuery.query = value;
			_activeQuery.response = null;
			_activeElement.value = value;
			_selectedValue = value;
		}
	};
 
	//selects next suggestion
	var _selectNext = function(){
		if(_currentSelection >= 0)
			_suggestionsPanel.childNodes[_currentSelection].className = 'suggest_link';
		
		if(_currentSelection == (_suggestionsPanel.childNodes.length - 1))				
			_currentSelection = 0;
		else
			++_currentSelection;
		
		_suggestionsPanel.childNodes[_currentSelection].className = 'suggest_link_over';
		
		if(_currentSelection > -1){
			var value = _suggestionsPanel.childNodes[_currentSelection].childNodes[0].nodeValue;
			_activeQuery.query = value;
			_activeQuery.response = null;
			_activeElement.value = value;
			_selectedValue = value;
		}
	};
		
	//displays the suggestions panel
	var _showSuggestions = function(){			
		_suggestionsPanel.style['display'] = 'block';
		_currentSelection = -1;
		
		var pos = _findPos(_trackingElement);
		
		_suggestionsPanel.style['left'] = pos.left + 'px';
		_suggestionsPanel.style['top'] = pos.top + _trackingElement.offsetHeight + 'px';
		_suggestionsPanel.style['width'] = _trackingElement.offsetWidth + 'px';
	};
	
	//hides suggestion panel
	var _hideSuggestions = function(){
		_suggestionsPanel.innerHTML = '';
		_suggestionsPanel.style['display'] = 'none';
	};
	
	this.insertScriptTag = function(){
		if(!_activeElement || _activeElement.value == _activeQuery.query) return;
		
		_trackingElement = _activeElement;			
		var str = escape(_activeElement.value);
		
		if(str == ''){
			_suggestionsPanel.style['display'] = 'none';
			return;
		}
		
		_activeQuery.query = str;
		
		// The web service call
		var host = 'suggest.infospace.com';  // Modify this for local testing
		loadScriptTag('//' + host + '/QuerySuggest/SuggestServlet?prefix=' + str);
	};
	
	//handles keyUp event on input edit boxes
	var _onEditKeyUp = function(e){
		if (!e) var e = window.event;
		var code;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		

		if(code != 27 && code != 40 && code != 38 && code != 13)
		window.setTimeout("iSuggest.insertScriptTag()", _timeoutPeriod);
	};
			
	//handles the global keyboard events 
	this.onKeyUp = function (e){
		if(_suggestionsPanel.style['display'] != 'block' && _suggestionsPanel.childNodes.length == 0) return;
		
		var code;
		if (e.keyCode) code = e.keyCode;
		else if (e.which) code = e.which;
		
		//alert(code);
		switch(code)
		{
			case 40: //handle key down
				_selectNext();
				break;
			case 38: //handle key up
				_selectPrevious();
				break;
			case 27: //handle esc
					_hideSuggestions();
					_activeQuery.query = null;
					_activeQuery.response = null;
				break;
		}
	};	
	
	this.PopulateResults = function(suggestions, reqID){
		_suggestionsPanel.innerHTML = '';
		_currentSelection = -1;
		
		if(suggestions){
			for(var i=0; i < suggestions.length; i++) {
				var suggest = '<div onmouseover="javascript:iSuggest.suggestOver(this);" onmouseout="javascript:iSuggest.suggestOut(this);" onclick="javascript:iSuggest.setSearch(this.childNodes[0].nodeValue);" class="suggest_link" id="suggest_link_'+ i + '">' + suggestions[i] + '</div>';
				_suggestionsPanel.innerHTML += suggest;
			}
		}
		
		removeScriptTagById(reqID);
		
		if(_suggestionsPanel.innerHTML == "" || suggestions == null)
			_hideSuggestions();
		else
			_showSuggestions();
	};
	
	//global onclick event. used to hide suggestions
	this.onMouseClick = function(){
		_hideSuggestions();
		_activeQuery.query = null;
		_activeQuery.response = null;
	};
	
	//Mouse over function
	this.suggestOver = function (div_value) { div_value.className = 'suggest_link_over'; };
	
	//Mouse out function
	this.suggestOut = function (div_value) { div_value.className = 'suggest_link'; };
	
	var _logSelection = function () {
		/*loadScriptTag(
			'http://wsapi.infospace.com/clickcallbackserver/_iceUrlFlag=1' +
			'?0=&1=0&4=67.63.63.64&5=67.63.63.64&9=8d3a32ecef0a4087816ca8aa342acb01&10=1&11=' +
			ispace_brand + '&14=116&15=other&40=cRGrgtElyOE%3D&_IceUrl=true'
		);*/
	};
	
	//Click function
	this.setSearch = function (value) {
			
		//Log the PT interaction by sending an asynchronous request to the clickcallbackserver
		_logSelection();
		
		_activeQuery.query = value;
		_activeQuery.response = null;
		_hideSuggestions();
		_activeElement.value = value;
		if (_changeCallbacks[_activeElement.id]) {
			_changeCallbacks[_activeElement.id]();
		}
		
		return false;
	};
	
	//initializes the component
	this.init = function(inputsToTrack){
 
		_suggestionsPanel = document.createElement('div');
		_suggestionsPanel.className = 'suggestPanel';
		_suggestionsPanel.id = 'suggestPanel';
		
		document.body.insertBefore(_suggestionsPanel, document.body.firstChild);
		
		for(var i=0; i < inputsToTrack.length; i++){
			var field = inputsToTrack[i];
			if (typeof(field) == "string") {
				this.addField(field);
			}
			else if (field.id) {
				this.addField(field.id);
				if (field.buttonId) {
					var buttonId = field.buttonId;
					_changeCallbacks[field.id] = function () {document.getElementById(buttonId).click()};
				}
				else if (field.formId) {
					var formId = field.formId;
					_changeCallbacks[field.id] = function () {document.getElementById(formId).submit()};
				}
				else if (field.callback) {
					var callback = field.callback;
					_changeCallbacks[field.id] = callback;
				}
			}
		}
	};
	
	//adds a text field to be tracked for suggestions
	this.addField = function(txtID){
		var textBox = document.getElementById(txtID);
			
		textBox.oldOnFocus = (textBox.onfocus) ? textBox.onfocus : function() {};
		function newOnFocus(e) {_activeElement = this; this.oldOnFocus();}
		textBox.onfocus = newOnFocus;
		
		textBox.oldOnBlur = (textBox.onblur) ? textBox.onblur : function() {};
		function newOnBlur(e) {if(_suggestionsPanel.style['display'] != 'block')_activeElement = null; this.oldOnBlur();}
		textBox.onblur = newOnBlur;
		
		textBox.oldEditKey = (textBox.onkeyup) ? textBox.onkeyup : function() {};
		function newEditKeyUp(e) {_activeElement = this; this.oldEditKey(); if (!e) var e = window.event; _onEditKeyUp(e);}
		textBox.onkeyup = newEditKeyUp;
		
		textBox.setAttribute('autocomplete','off');
	};
}
 
//global suggest client object
var iSuggest;
 
//Initializes the page for suggestions.
function Initialize() {	
	iSuggest = new ISuggest();
	iSuggest.init(ispace_txtElements);
	
	//hook events
	var oldOnClick = (document.onclick) ? document.onclick : function() {};
	function newOnClick(e) {oldOnClick(); if (!e) var e = window.event; iSuggest.onMouseClick(e);}
	document.onclick = newOnClick;
	
	var oldOnKeyUp = (document.onkeyup) ? document.onkeyup : function() {};
	function newOnKeyUp(e) {oldOnKeyUp(); if (!e) var e = window.event; iSuggest.onKeyUp(e);}
	document.onkeyup = newOnKeyUp;
}
 
 
 
// Write the cache busting IFRAME
document.write('<iframe style="height:0px;width:0px;visibility:hidden;display:none;" src="about:blank">this prevents back forward cache</iframe>');
 
// Write the minimum styles
document.writeln('<style type="text/css">');
document.writeln('.suggest_link {overflow:hidden;}');
document.writeln('.suggest_link_over {overflow:hidden;cursor:pointer;}');
document.writeln('.suggestPanel {position:absolute;display:none;text-align:left;}');
document.writeln('</style>');
 
Initialize();