<script data-main="/elFinder/main.js" src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.5/require.min.js"></script>
<script>
	define('elFinderConfig', {
		// elFinder options (REQUIRED)
		// Documentation for client options:
		// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
		defaultOpts : {
			ui:  [ 'toolbar', 'path' ] ,
			defaultView: 'list',
			sortType: 'date',
			sortOrder: 'desc',
			uiOptions: {
				toolbar: [
					['back', 'up', 'reload'],
					['mkdir', 'upload'],
					['download'],
					['rm'],
					['extract','archive'],
					['share']
				],
				toolbarExtra: {
					displayTextLabel: true
				},
				cwd: {
					listView: {
						columns: ['size','kind','date']
					}
				}
			},
			url : '/elFinder/php/connector.minimal.php?project_id=<?=$id;?>',
			commandsOptions : {
				edit : {
					extraOptions : {
						// set API key to enable Creative Cloud image editor
						// see https://console.adobe.io/
						creativeCloudApiKey : '',
						// browsing manager URL for CKEditor, TinyMCE
						// uses self location with the empty value
						managerUrl : ''
					}
				}
				,quicklook : {
					// to enable CAD-Files and 3D-Models preview with sharecad.org
					sharecadMimes : ['image/vnd.dwg', 'image/vnd.dxf', 'model/vnd.dwf', 'application/vnd.hp-hpgl', 'application/plt', 'application/step', 'model/iges', 'application/vnd.ms-pki.stl', 'application/sat', 'image/cgm', 'application/x-msmetafile'],
					// to enable preview with Google Docs Viewer
					googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/postscript', 'application/rtf'],
					// to enable preview with Microsoft Office Online Viewer
					// these MIME types override "googleDocsMimes"
					officeOnlineMimes : ['application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.presentation']
				}
			}
			// bootCalback calls at before elFinder boot up 
			,bootCallback : function(fm, extraObj) {
				/* any bind functions etc. */
				fm.bind('init', function() {
					//Code
				});
				// for example set document.title dynamically.
				var title = document.title;
				fm.bind('open', function() {
					var path = '',
						cwd  = fm.cwd();
					if (cwd) {
						path = fm.path(cwd.hash) || null;
					}
					document.title = path? path + ':' + title : title;
				}).bind('destroy', function() {
					document.title = title;
				});
			}

		},
		managers : {
			// 'DOM Element ID': { /* elFinder options of this DOM Element */ }
			'elfinder': {}
		}
	});
</script>

<div id="elfinder"></div>