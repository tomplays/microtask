/**
* JavaScript routines for Krumo
*
* @version $Id: krumo.js 22 2007-12-02 07:38:18Z Mrasnika $
* @link http://sourceforge.net/projects/krumo
*/

/////////////////////////////////////////////////////////////////////////////

/**
* Krumo JS Class
*/
function krumo() {
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

/**
* Add a CSS class to an HTML element
*
* @param HtmlElement el 
* @param string className 
* @return void
*/
krumo.reclass = function(el, className) {
	if (el.className.indexOf(className) < 0) {
		el.className += (' ' + className);
		}
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

/**
* Remove a CSS class to an HTML element
*
* @param HtmlElement el 
* @param string className 
* @return void
*/
krumo.unclass = function(el, className) {
	if (el.className.indexOf(className) > -1) {
		el.className = el.className.replace(className, '');
		}
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

/**
* Toggle the nodes connected to an HTML element
*
* @param HtmlElement el 
* @return void
*/
krumo.toggle = function(el) {
	var ul = el.parentNode.getElementsByTagName('ul');
	for (var i=0; i<ul.length; i++) {
		if (ul[i].parentNode.parentNode == el.parentNode) {
			ul[i].parentNode.style.display = (ul[i].parentNode.style.display == 'none')
				? 'block'
				: 'none';
			}
		}

	// toggle class
	//
	if (ul[0].parentNode.style.display == 'block') {
		krumo.reclass(el, 'krumo-opened');
		} else {
		krumo.unclass(el, 'krumo-opened');
		}
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

/**
* Hover over an HTML element
*
* @param HtmlElement el 
* @return void
*/
krumo.over = function(el) {
	krumo.reclass(el, 'krumo-hover');
	}

// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

/**
* Hover out an HTML element
*
* @param HtmlElement el 
* @return void
*/

krumo.out = function(el) {
	krumo.unclass(el, 'krumo-hover');
	}


/////////////////////////////////////////////////////////////////////////////
// Ajout perso

krumo._expandOrCollapse = function(el, expand) {
	var ul = el.parentNode.getElementsByTagName('ul');
	for (var i=0; i<ul.length; i++) {
		if (ul[i].parentNode.parentNode == el.parentNode) {
			ul[i].parentNode.style.display = (expand) ? 'block' : 'none';
		}
	}
	if (expand) {
		krumo.reclass(el, 'krumo-opened');
	} else {
		krumo.unclass(el, 'krumo-opened');
	}
}

krumo.expandOrCollapse = function(krumoRootElem, rootOnly, expand) {
	var krumoElems = krumoRootElem.querySelectorAll("div.krumo-expand");
	for (var i = 0, n = krumoElems.length; i < n; ++i){
		// On ouvre les noeuds (à l'exception des champs de texte, qui peuvent être long)
		if (krumoElems[i].querySelector("em.krumo-type").innerText.indexOf("String") < 0) {
			krumo._expandOrCollapse(krumoElems[i], rootOnly && i > 0 ? false : expand);
		}
	}
}

krumo.expand = function(krumoRootElem, rootOnly) {
	krumo.expandOrCollapse(krumoRootElem, rootOnly, true);
}

krumo.collapse = function(krumoRootElem, rootOnly) {
	krumo.expandOrCollapse(krumoRootElem, rootOnly, false);
}

krumo.expandOrCollapseAll = function(rootOnly, expand) {
	var krumoElems = document.querySelectorAll(".krumo-root");
	for (var i = 0, n = krumoElems.length; i < n; ++i){
		krumo.expandOrCollapse(krumoElems[i], rootOnly, expand);
	}
}

krumo.expandAll = function(rootOnly) {
	krumo.expandOrCollapseAll(rootOnly, true);
}

krumo.collapseAll = function(rootOnly) {
	krumo.expandOrCollapseAll(rootOnly, false);
}


krumo.toggleExpand = function(krumoRootElem, rootOnly, expand) {
	var krumoElems = krumoRootElem.querySelectorAll("div.krumo-expand");
	krumoElems     = rootOnly ? [krumoElems[0]] : krumoElems;
	for (var i = 0, n = krumoElems.length; i < n; ++i){
		// On ouvre les noeuds (à l'exception des champs de texte, qui peuvent être long)
		if (krumoElems[i].querySelector("em.krumo-type").innerText.indexOf("String") < 0) {
			krumo.expandOrCollapse(krumoElems[i], expand);
		}
	}
}

// Expand au démarrage
// document.addEventListener('DOMContentLoaded',function(){
// 	krumo.toggleExpandAll();
// 	krumo.toggleExpand(document.querySelectorAll(".krumo-root")[0]);
// });

/////////////////////////////////////////////////////////////////////////////
