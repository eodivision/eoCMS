/*
 * bbCodePress v1.00.1
 * (c) 2009 - Paul Wratt
 *
 * This code is just used to insert bulletin board codes (bbcode) into a text box.
 * It is not designed to render or convert bbcodes to or from html, that requires 
 * the full version of bbCodePress.
 *
 * Include this code with the following html tag:
 *  <script type=text/javascript src="./bbcodepress/bbcodepress-lite.js" ></script>
 *
 * Use with a plain textarea (as opposed to a scripted editor control)
 * add "bbcodepress" to the class tag (see example.html)
 * give the textarea an id (see example.html)
 *
 * other files useful with this code
 *  bbcode-simple.lst
 *  smiley-simple.lst
 *  bbcodepress-lite.php
 *  bbcodepress-lite.asp
 *  example.html
 *  example.php
 *  example.asp
 *
 * to change the list of bbcodes used, the key codes, or images, see the list files 
 * and the examples
 *
 * This code was designed as a replacement for "MarkItUp" for eoCMS (http://eocms.com).
 * It is "loosely" based on the (now old) CodePress Object (color coded text editor)
 * currently used by PHP Navigator (http://navphp.sourceforge.net/).
 *
 * as of 08-May-2009, the following browsers have been tested:
 * Browser:               -Result:
 *  FireFox 2             - 100%
 *  FireFox 3             - 100%
 *  Safari 4              - 100%, but choose key code carefully
 *  Konquror (KHTML)      - 100%, cursor position after insert is arbitrary
 *  MS IE 7               - 100%, but choose key code carefully
 *  Flock                 - 100%
 *  Opera                 - 100%
 *
 * NOTE: most modern browsers use a lot of CTRL+(letter) quick keys, not all key strokes 
 *       can be blocked, and each browser has its own way of dealing with that.
 *
 * http://bbcode.isource.net.nz/
 * http://paulwratt.110mb.com/bbcode/
 */

bbCodePress = function(xObj) {

	// set initial vars
	this.textarea      = xObj;
	self.textid        = xObj.id;
	this.scrollTop     = 0;
	this.scrollLeft    = 0;
	this.selectStart   = 0;
	this.selectEnd     = 0;
	self.keyBind       = new Array();
	self.shiftBind     = false;
	self.ctrlBind      = false;
	self.altBind       = false;

	// set up key capture
	this.initialize = function() {
		s = document.getElementsByTagName('script');
		for(var i=0,n=s.length;i<n;i++) {
			if(s[i].id==(this.textarea.id+'_regKeys')) {
				eval(this.textarea.id+'_Keys();');
			} 
		}
		if(window.attachEvent) document.attachEvent('onkeydown', this.keyHandler);
		else document.addEventListener('keypress', this.keyHandler, true);
	}

	// register key bindings
	this.registerKey = function(xKeyCombo,xPasteValue) {
		xKeyCombo = xKeyCombo.replace(/\+/g,'-');
		xChar = xKeyCombo;
		if(xKeyCombo.match('-') && xKeyCombo!=='-') {
			xChar = xKeyCombo.substring(xKeyCombo.lastIndexOf('-')+1,xKeyCombo.length);
			if (xKeyCombo.match('alt-'))   self.altBind   = true;
			if (xKeyCombo.match('ctrl-'))  self.ctrlBind  = true;
			if (xKeyCombo.match('shift-')) self.shiftBind = true;
		}
		self.keyBind[self.keyBind.length] = {
			altKey:   (xKeyCombo.match('alt-')   ? true : false),
			ctrlKey:  (xKeyCombo.match('ctrl-')  ? true : false),
			shiftKey: (xKeyCombo.match('shift-') ? true : false),
			charCodeU: xChar.toUpperCase().charCodeAt(0),
			charCodeL: xChar.toLowerCase().charCodeAt(0),
			paste:     xPasteValue
		}
	}

	// treat key bindings
	this.keyHandler = function(evt) {
		if (self.keyBind.length==0) return;

		keyCode = (evt.charCode) ? evt.charCode : evt.keyCode;	

		if((evt.ctrlKey || evt.metaKey) && !self.ctrlBind) return;
		if((evt.shiftKey) && !self.shiftBind) return;
		if((evt.altKey) && !self.altBind) return;
		for(i=0,n=self.keyBind.length;i<n;i++){
			if((evt.ctrlKey==self.keyBind[i].ctrlKey || evt.metaKey==self.keyBind[i].ctrlKey) && (evt.shiftKey==self.keyBind[i].shiftKey) && (evt.altKey==self.keyBind[i].altKey) && (keyCode==self.keyBind[i].charCodeU || keyCode==self.keyBind[i].charCodeL)) {
				setTimeout(self.textid + '_cp.insertText("' + self.keyBind[i].paste + '");',10);
				if(window.attachEvent) evt.returnValue = false;
				if(!evt.preventDefault) evt.preventDefault = function () { return true; }
				if(!evt.stopPropagation) evt.stopPropagation = function () { if(window.event) window.event.cancelBubble = true; }
				evt.preventDefault();
				evt.stopPropagation();
				return(false);
			}
		}
	}

	this.insertText = function(xText){
		self.selectStart = this.selectStart();
		xCut = this.textCut();
		this.textPaste(xText.replace('$0',xCut));
		if(xCut=='' && xText.indexOf('$0')!=-1){
			xCaret = self.selectStart + xText.indexOf('$0');
			xCaretOffset = (xText.indexOf('$0') + 2) - xText.length;
			this.saveEditor();
//			this.setSelect(xCaret,xCaret);  // set selected area
//			this.setCaret(xCaret);          // set cursor position
			this.adjustCaret(xCaretOffset); // adjust cursor from "current" position
		}
		this.textarea.focus();
	}

	this.saveEditor = function(){
		self.scrollTop     = this.textarea.scrollTop;
		self.scrollLeft    = this.textarea.scrollLeft;
		self.selectStart   = this.selectStart();
		self.selectEnd     = this.selectEnd();
	}

	this.selectStart = function(){
		if (!window.innerHeight){
			this.textarea.focus();
			sel = document.selection.createRange();
			range = sel.duplicate();
			range.moveToElementText(this.textarea);
			range.setEndPoint('EndToEnd',sel);
			this.textarea.selectionStart = range.text.length - sel.text.length;

		}
		return(this.textarea.selectionStart);
	}

	this.selectEnd = function(){
		if (!window.innerHeight){
			this.textarea.focus();
			sel = document.selection.createRange();
			this.textarea.selectionEnd = this.textarea.selectionStart + sel.text.length;
		}
		return(this.textarea.selectionEnd);
	}

	this.adjustCaret = function(xCount){
		if (!window.innerHeight){
			this.textarea.focus();
			sel = document.selection.createRange();
			sel.moveStart('character', xCount);
			sel.moveEnd('character', xCount);
			sel.select();
		}
		this.textarea.selectionStart += xCount;
		this.textarea.selectionEnd += xCount;
	}

	this.setCaret = function(xVal){
		if (!window.innerHeight){
			this.textarea.focus();
			sel = document.selection.createRange();
			sel.moveStart('character', xVal - this.textarea.selectionStart);
			sel.moveEnd('character', xVal - this.textarea.selectionEnd);
			sel.select();
		}
		this.textarea.selectionStart = xVal;
		this.textarea.selectionEnd = xVal;
	}

	this.setSelect = function(xValStart,xValEnd){
		if (!window.innerHeight){
			this.textarea.focus();
			sel = document.selection.createRange();
			sel.moveStart('character', xValStart -this.textarea.selectionStart);
			sel.moveEnd('character', xValEnd -this.textarea.selectionEnd);
			sel.select();
		}
		this.textarea.selectionStart = xValStart;
		this.textarea.selectionEnd = xValEnd;
	}

	this.setSelectStart = function(xVal){
		if (document.selection){
			this.textarea.focus();
			sel = document.selection.createRange();
			sel.collapse(true);
			sel.moveStart('character', xVal - this.textarea.selectionStart);
			sel.select();
		}
		this.textarea.selectionStart = xVal;
	}

	this.setSelectEnd = function(xVal){
		if (!window.innerHeight){
			this.textarea.focus();
			sel = document.selection.createRange();
			sel.moveEnd('character', xVal - this.textarea.selectionEnd );
			sel.select();
		}
		this.textarea.selectionEnd = xVal;
	}

	this.textCut = function(){
		if (!window.innerHeight){
			this.textarea.focus();
			xSel = document.selection.createRange();
			xText = xSel.text;
			xSel.text = '';
		}else{
			xStart = this.textarea.selectionStart;
			xText = this.textarea.value.substring(this.textarea.selectionStart,this.textarea.selectionEnd);
			this.textarea.value = this.textarea.value.substring(0,this.textarea.selectionStart) + this.textarea.value.substring(this.textarea.selectionEnd,this.textarea.value.length);
			this.textarea.selectionStart = xStart;
			this.textarea.selectionEnd = xStart;
		}
		return(xText);
	}

	this.textCopy = function(){
		if (!window.innerHeight){
			this.textarea.focus();
			xSel = document.selection.createRange();
			xText = xSel.text;
		}else{
			xText = this.textarea.value.substring(this.textarea.selectionStart,this.textarea.selectionEnd);
		}
		this.textarea.focus();
		return(xText);
	}

	this.textPaste = function(xPaste){
		if (!window.innerHeight){
			this.textarea.focus();
			xSel = document.selection.createRange();
			xSel.text = xPaste;
			xSel.select();
		}else{
			xStart = this.textarea.selectionStart;
			xText = this.textarea.value.substring(this.textarea.selectionStart,this.textarea.selectionEnd);
			this.textarea.value = this.textarea.value.substring(0,this.textarea.selectionStart) + xPaste + this.textarea.value.substring(this.textarea.selectionEnd,this.textarea.value.length);
			this.textarea.selectionStart = xStart + xPaste.length;
			this.textarea.selectionEnd = xStart + xPaste.length;
		}
	}
}

bbCodePress.noId = 0;

bbCodePress.run = function() {
	t = document.getElementsByTagName('textarea');
	for(var i=0,n=t.length;i<n;i++) {
		if(t[i].className.match('bbcodepress')) {
			id = t[i].id;
			if(id=='') {
				id = t[i].id = 'bbcode' + bbCodePress.noId;
				++bbCodePress.noId;
			}
			eval(id+'_cp = new bbCodePress(t[i]);');
			eval(id+'_cp.initialize();');
		} 
	}
}

if (window.attachEvent) window.attachEvent('onload',bbCodePress.run);
//else window.addEventListener('DOMContentLoaded',bbCodePress.run,false); // does not work in certain browsers
else window.addEventListener('load',bbCodePress.run,false);
