/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 12:36
 */
var versionAdd = function(versionData, libData){
    if ( versionData.data &&
      versionData.data.files_tree &&
      versionData.data.files_tree.length &&
      versionData.data.languages_tree
    ){
      bbn.fn.popup($("#932f9u4923rjasdu09j3333").html(), kendo.format(data.lng.add_version, libData.data.title), bbn.env.width - 100, false, function(cont){

        var obs = new kendo.observable(versionData.data);

        var kcont = cont.data("kendoWindow");

        // Show form
        $("#asdahf8923489yhf98923hr", cont).show();

        kendo.bind(cont, obs);

        obs.bind("change", function(a, b){
          bbn.fn.log("CHANGED", a, b, this);
        });

        bbn.fn.log(versionData.data.version ? "V " + versionData.data.version : "NO version");

        // Set version's name readonly
        $("#u93248safn328dasuq89yu", cont).attr('readonly', 'readonly');

        $("#u93248safn328dasuq89yu", cont).click(function(){bbn.fn.log(obs.get("version"))});

        // Create files treeviews
        $("#ashd3538y1i35h8oasdj023", cont).kendoTreeView({
          dataSource: versionData.data.files_tree,
          checkboxes: {
            checkChildren: true
          },
          check: function(c){
            var dataItem = c.sender.dataItem(c.node);
            // Add/remove file to/from files reorder list
            addDelFilesOrder(dataItem);
          },
          expand: function(){
            setTimeout(function(){
              // Center the window
              kcont.center();
            }, 300);
          },
          collapse: function(){
            setTimeout(function(){
              // Center the window
              kcont.center();
            }, 300);
          }
        });

        // Drag&Drop files reorder
        $("#joisfd8723hifwe78238hds", cont).kendoDraggable({
          filter: 'div.k-alt',
          group: 'filesGroup',
          threshold: 100,
          axis: 'y',
          hint: function(e){
            return e.clone().css('background-color', 'green');
          }
        });
        $("#joisfd8723hifwe78238hds", cont).kendoDropTarget({
          group: 'filesGroup',
          drop: function(dt){
            dt.draggable.hint.hide();
            var targ = dt.draggable.currentTarget,
              dest = $(document.elementFromPoint(dt.clientX, dt.clientY)),
              targPos = targ[0].offsetTop,
              destPos = dest[0].offsetTop;
            if ( (targ.attr('path') !== undefined) &&
              (dest.attr('path') !== undefined) &&
              (targ.attr('path') !== dest.attr('path'))
            ){
              targ.remove();
              if ( targPos < destPos ){
                dest.after(targ);
              }
              else {
                dest.before(targ);
              }
            }
          }
        });

        // Create languages grid
        $("#y7hhiawza3u9y983w2asj9h9xe4", cont).kendoGrid({
          dataSource: {
            data: []
          },
          editable: 'popup',
          columns: [{
            title: data.lng.files,
            field: 'path'
          }, {
            title: '',
            width: 50,
            headerTemplate: '<a href="javascript:;" class="k-button k-button-icontext k-grid-add fa fa-plus add-lang"></a>',
            headerAttributes: {
              style: "text-align: center"
            },
            command: [{
              name: "destroy",
              test: data.lng.del,
              template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
            }]
          }]
        });

        // Insert new language file
        $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4", cont).on("click", function(){
          bbn.fn.popup($("#9342ja823hioasfy3oi").html(), data.lng.add_language, 850, false, function(a){
            $("#845hiay8h9fhuwiey823hi", a).kendoTreeView({
              dataSource: versionData.data.languages_tree,
              select: function(s){
                $("input[name=path]", a).val(s.sender.dataItem(s.node).path);
              },
              expand: function(){
                setTimeout(function(){
                  a.data("kendoWindow").trigger("resize");
                }, 300);
              },
              collapse: function(){
                setTimeout(function(){
                  a.data("kendoWindow").trigger("resize");
                }, 300);
              }
            });
            $("a.k-button:first", a).on("click", function(){
              $("#y7hhiawza3u9y983w2asj9h9xe4", cont).data("kendoGrid").dataSource.add({path: $("input[name=path]", a).val()});
              bbn.fn.closePopup();
            });
            $("a.k-button:last", a).on("click", function(){
              bbn.fn.closePopup();
            });
          });
        });

        // Create themes grid
        $("#y99hu8y4ss3a2s5423ld453wmn", cont).kendoGrid({
          dataSource: {
            data: []
          },
          editable: 'popup',
          columns: [{
            title: data.lng.files,
            field: 'path'
          }, {
            title: '',
            width: 50,
            headerTemplate: '<a href="javascript:;" class="k-button k-button-icontext k-grid-add fa fa-plus add-theme"></a>',
            headerAttributes: {
              style: "text-align: center"
            },
            command: [{
              name: "destroy",
              test: data.lng.del,
              template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
            }]
          }]
        });
        // Insert new theme file
        $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn", cont).on("click", function(){
          bbn.fn.popup($("#9342ja823hioasfy3oi").html(), data.lng.add_theme_file, 850, false, function(a){
            $("#845hiay8h9fhuwiey823hi", a).kendoTreeView({
              dataSource: versionData.data.files_tree,
              select: function(s){
                $("input[name=path]", a).val(s.sender.dataItem(s.node).path);
              },
              expand: function(){
                setTimeout(function(){
                  a.data("kendoWindow").trigger("resize");
                }, 300);
              },
              collapse: function(){
                setTimeout(function(){
                  a.data("kendoWindow").trigger("resize");
                }, 300);
              }
            });
            $("a.k-button:first", a).on("click", function(){
              $("#y99hu8y4ss3a2s5423ld453wmn", cont).data("kendoGrid").dataSource.add({path: $("input[name=path]", a).val()});
              bbn.fn.closePopup();
            });
            $("a.k-button:last", a).on("click", function(){
              bbn.fn.closePopup();
            });
          });
        });

        // Set latest checked
        $("#hw4o5923noasd890324yho", cont).attr({checked: 'checked'}).click(function(){
          var eleCont = $(this).closest("div.bbn-form-field");
          if ( $(this).is(':checked') ){
            var dd = $("#khasdknasduiiyi3rhas", cont).data("kendoDropDownList");
            dd.destroy();
            dd.wrapper.remove();
          }
          else {
            var internals = [{
              text: '0',
              value: '0'
            }];
            if ( versionData.data.internal && versionData.data.internal.length ){
              internals = versionData.data.internal;
            }
            $('<input id="khasdknasduiiyi3rhas">').appendTo(eleCont).kendoDropDownList({
              dataSource: internals,
              dataTextField: 'text',
              dataValueField: 'value'
            });
          }
        });

        // Dependecies grid
        $("#732ijfasASdha92389yasdh9823", cont).kendoGrid({
          dataSource: {
            data: versionData.data.dependencies,
            schema: {
              model :{
                id: "id_ver",
                fields: {
                  lib_title: { type: "string", validation: { required: true } },
                  lib_name: { type: "string", validation: { required: true } },
                  version: { type: "string", validation: { required: true } },
                  internal: { type: "number" },
                  id_ver: { type: "number", validation: { required: true } },
                  order: { type: "number", validation: { required: true } }
                }
              }
            }
          },
          editable: 'inline',
          sortable: true,
          columns: [{
            title: data.lng.library,
            field: 'lib_name',
            template: function(e){
              return e.lib_title ? e.lib_title : e.lib_name;
            },
            editor: function(container, options){
              var libraries = [];
              $.each(versionData.data.lib_ver, function(i,e){
                if ( bbn.fn.search(libraries, 'lib_name', e.lib_name) < 0 ){
                  libraries.push(e);
                }
              });
              $('<input name="' + options.field + '" style="width: 100%" required>').appendTo(container).kendoDropDownList({
                dataSource: libraries,
                dataValueField: "lib_name",
                dataTextField: "lib_title",
                change: function(e){
                  var ddVersion = container.next().find("input:hidden").data("kendoDropDownList");
                  options.model.set('lib_name', e.sender.value());
                  options.model.set('lib_title', e.sender.text());
                  ddVersion.dataSource.filter({
                    field: "lib_name",
                    operator: "equal",
                    value: options.model.lib_name
                  });
                  ddVersion.select(0);
                  ddVersion.trigger('change');
                }
              });
            }
          }, {
            title: data.lng.version,
            field: 'id_ver',
            template: function(e){
              return e.version;
            },
            editor: function(container, options){
              $('<input name="' + options.field + '" style="width: 100%" required>').appendTo(container).kendoDropDownList({
                dataSource: {
                  data: versionData.data.lib_ver,
                  filter: {
                    field: "lib_name",
                    operator: "equal",
                    value: options.model.lib_name
                  }
                },
                dataValueField: "id_ver",
                dataTextField: "version",
                change: function(e){
                  options.model.set('version', e.sender.text());
                  options.model.set('id_ver', e.sender.value());
                }
              });
            }
          }, {
            title: data.lng.order,
            field: 'order',
            width: 80,
            format: "{0:n0}"
          }, {
            title: '',
            width: 100,
            headerTemplate: '<a href="#" class="k-button k-grid-add fa fa-plus add-dep"></a>',
            headerAttributes: {
              style: "text-align: center"
            },
            attributes: {
              style: "text-align: center"
            },
            command: [{
              name: 'edit',
              text: {
                edit: '',
                cancel: '',
                update: ''
              },
              click: function(){
                $("a.k-button-icontext", "#732ijfasASdha92389yasdh9823", cont).removeClass("k-button-icontext");
              }
            }, {
              name: 'destroy',
              template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
            }]
          }],
          dataBound: function(){
            $("a.k-button-icontext", "#732ijfasASdha92389yasdh9823", cont).removeClass("k-button-icontext");
            $("a.add-dep", cont).click(function(){
              $("#732ijfasASdha92389yasdh9823", cont).data("kendoGrid").addRow();
              $("a.k-button-icontext", "#732ijfasASdha92389yasdh9823", cont).removeClass("k-button-icontext");
            });
          },
          edit: function(e){
            if ( !e.model.id ){
              var d = e.sender.dataSource.data(),
                max = 0;
              $.each(d, function(i, v){
                if ( v.order > max ){
                  max = v.order;
                }
              });
              e.model.set('order', max+1);
            }
          }
        });

        // Slave dependencies
        if ( versionData.data.slave_dependencies && versionData.data.slave_dependencies.length ){
          $("#asdahf8923489yhf98923hr", cont).append($("#hfashio3289yasdnuqwy8232").html());
          var cbCont = $("form", "div.bbn-form-field:last", cont);
          $.each(versionData.data.slave_dependencies, function(i, e){
            $('<div>' +
              '<input id="' + i + 'sd_' + e.name + '" class="k-checkbox" type="checkbox"' +
              ' name="slave_dep[' + e.name + ']" value="1" checked="checked">' +
              '<label class="k-checkbox-label" for="' + i + 'sd_' + e.name + '">' + e.title + '</label>' +
              '</div>').appendTo(cbCont);
          });
        }

        // Check all slave dependencies
        $("i.ds-check.fa-check-square-o", cont).parent().click(function(){
          $("input:checkbox", $(this).closest("div.bbn-form-field")).prop("checked", true);
        });

        // Uncheck all slave dependecies
        $("i.ds-check.fa-square-o", cont).parent().click(function(){
          $("input:checkbox:checked", $(this).closest("div.bbn-form-field")).prop("checked", false);
        });

        // Add save and cancel buttons
        $("#asdahf8923489yhf98923hr", cont).append(
          '<div class="bbn-form-label" style="padding: 0; width: 120px"></div>' +
          '<div class="bbn-form-field" style="text-align: right">' +
          '<a href="#" class="k-button k-button-icontext" style="margin-right: 5px">' +
          '<span class="k-icon k-update"></span>' +
          data.lng.save +
          '</a>' +
          '<a href="#" class="k-button k-button-icontext">' +
          '<span class="k-icon k-cancel"></span>' +
          data.lng.cancel +
          '</a>' +
          '</div>'
        );
        $("span.k-update", cont).parent().on("click", function(){
          var files = [],
            latest = $("#hw4o5923noasd890324yho:checked", cont).length;
          $("div", "#joisfd8723hifwe78238hds", cont).each(function(i,v){
            files.push($(v).attr('path'));
          });
          bbn.fn.post('cdn/actions/version/add', {
            name: libData.data.name,
            vname: versionData.data.version,
            files: JSON.stringify(files),
            languages: JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4", cont).data("kendoGrid").dataSource.data()),
            themes: JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn", cont).data("kendoGrid").dataSource.data()),
            latest: latest,
            internal: $("#khasdknasduiiyi3rhas", cont).length ? $("#khasdknasduiiyi3rhas", cont).data("kendoDropDownList").value() : '',
            dependencies: $("#732ijfasASdha92389yasdh9823", cont).data("kendoGrid").dataSource.data().toJSON(),
            slave_dependencies: bbn.fn.formdata($("form", "div.bbn-form-field", cont)).slave_dep
          }, function(i){
            if ( i.data.success ){
              if ( latest ){
                var uidRow = libData.data.uid;
                libData.data.set('latest', versionData.data.version);
                librariesGrid.data("kendoGrid").expandRow("tr[data-uid=" + uidRow + "]");
              }
              else {
                $("div.k-grid", libData.detailCell, ele).data("kendoGrid").dataSource.read();
              }
              bbn.fn.closePopup();
            }
          });
        });
        $("span.k-cancel", cont).parent().on("click", function(){
          bbn.fn.closePopup();
        });
      });
    }
    else if ( versionData.data && versionData.data.github ) {
      bbn.fn.confirm(data.lng.versionGithubImport, function(){
        fromGitHub(versionData.data.github, libData);
      });
    }
    else {
      bbn.fn.alert(data.lng.noNewVersion);
    }
};