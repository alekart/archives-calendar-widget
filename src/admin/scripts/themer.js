
var Editor = function () {
	this.nav = jQuery('.nav-tab-wrapper.custom').find('.nav-tab');
	this.tabs = jQuery('.tabs > .tab');
	this.active = 0;
	this.navActiveClass = 'nav-tab-active';
	this.tabActiveClass = 'active-tab';
	this.tabindex = jQuery('#tab-index');

	this._initEditors();
};

Editor.prototype.open = function (index) {
	if (this.active == index)
		return;

	var self = this,
		tabs = self.tabs,
		nav = self.nav,
		active = self.active;

	jQuery(nav[active]).removeClass(self.navActiveClass);
	jQuery(nav[index]).addClass(self.navActiveClass);

	jQuery(tabs[active]).removeClass(self.tabActiveClass);
	jQuery(tabs[index]).addClass(self.tabActiveClass);

	self.active = index;

	self.tabindex.val(index);

	self.applyCss();
};

Editor.prototype._initEditors = function() {
	this.editors = [
		ace.edit("editor1"),
		ace.edit("editor2")
	];

	jQuery.each(this.editors, function(index, item){
		item.setTheme("ace/theme/monokai");
		item.getSession().setMode("ace/mode/css");
		item.on('change', function () {
			var src = '#codesource' + (index + 1);
			var code = item.getValue();
			jQuery(src).html(code);
			jQuery('#arwprev').html(code);
		});
	});
};

Editor.prototype.applyCss = function() {
	var src = '#codesource' + (this.active + 1);
	var style = jQuery(src).html();
	jQuery('#arwprev').html(style);

	jQuery.each(this.editors, function(index, item){
		item.resize();
	});
};

Editor.prototype.openFileDialog = function() {
	var elem = document.getElementById('files');
	if(elem && document.createEvent) {
		var evt = document.createEvent("MouseEvents");
		evt.initEvent("click", true, false);
		elem.dispatchEvent(evt);
	}
};

Editor.prototype.handleFileSelect = function(evt) {
	var self = this,
		files = evt.target.files; // FileList object

	// files is a FileList of File objects. List some properties.
	if (!files.length) {
		alert('Please select a file!');
		return;
	}

	var file = files[0],
		start = 0,
		stop = file.size - 1,
		reader = new FileReader();

	// If we use onloadend, we need to check the readyState.
	reader.onloadend = function(evt) {
		if (evt.target.readyState == FileReader.DONE) { // DONE == 2
			var code = evt.target.result,
				theme;
			if(code.match(/theme1/)){
				theme = 0;
			}
			else if(code.match(/theme2/)){
				theme = 1;
			}
			else {
				code = "";
				return;
			}
			self.editors[theme].setValue(code);

			if(self.active != theme) {
				self.open(theme);
			}
		}
	};

	var blob = file.slice(start, stop + 1);
	reader.readAsBinaryString(blob);
};

jQuery(function ($) {
	var editor = new Editor();

	if(typeof editor_tab != 'undefined'){
		editor.open(editor_tab);
	}

	$('#theme-css-editor').on('click', 'a.nav-tab', function (e) {
		e.preventDefault();
		editor.open( $(this).index() );
	});

	$('#import-theme').on('click', function(e){
		e.preventDefault();
		editor.openFileDialog();
	});

	$('#files').on('change', function(e){
		editor.handleFileSelect(e);
		$(this).val('');
	});
});
