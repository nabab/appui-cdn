/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 12:36
 */
var versionEdit = function(e){
  var cont = e.container,
    kcont = cont.data("kendoWindow");

  // Remove all field
  $("div.k-edit-field, div.k-edit-label", cont).remove();
  // Insert template
  $("div.k-edit-form-container", cont).prepend($("#932f9u4923rjasdu09j3333").html());
  appui.fn.post('cdn/data/version/edit', {version: e.model.id}, function(p){
    if ( p.data ){
      // Show form
      $("#asdahf8923489yhf98923hr", cont).show();
      // Bind data
      kendo.bind($("#asdahf8923489yhf98923hr", cont), e.model);
      // Set version's name
      $("#u93248safn328dasuq89yu", cont).val(e.model.name).attr('readonly', 'readonly');
      // Create files treeviews
      $("#ashd3538y1i35h8oasdj023", cont).kendoTreeView({
        dataSource: p.data.files_tree,
        checkboxes: {
          checkChildren: true
        },
        check: function(c){
          var dataItem = c.sender.dataItem(c.node);
          // Set the model edited
          e.model.dirty = true;
          // Add/emove file to/from files reorder list
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
      // Add checked files to files reoder list
      addDelFilesOrder(p.data.files, true);
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
            e.model.dirty = true;
          }
        }
      });
      // Create languages grid
      $("#y7hhiawza3u9y983w2asj9h9xe4", cont).kendoGrid({
        dataSource: {
          data: p.data.languages
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
            name: 'destroy',
            test: data.lng.del,
            template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
          }]
        }],
        remove: function(){
          e.model.dirty = true;
        }
      });
      // Insert new language file
      $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", cont).on("click", function(){
        appui.fn.popup($("#9342ja823hioasfy3oi").html(), data.lng.add_language, 850, false, function(w){
          $("#845hiay8h9fhuwiey823hi", w).kendoTreeView({
            dataSource: p.data.languages_tree,
            select: function(s){
              $("input[name=path]", w).val(s.sender.dataItem(s.node).path);
            },
            expand: function(){
              setTimeout(function(){
                w.data("kendoWindow").trigger("resize");
              }, 300);
            },
            collapse: function(){
              setTimeout(function(){
                w.data("kendoWindow").trigger("resize");
              }, 300);
            }
          });
          $("a.k-button:first", w).on("click", function(){
            $("#y7hhiawza3u9y983w2asj9h9xe4", cont).data("kendoGrid").dataSource.add({path: $("input[name=path]", w).val()});
            e.model.dirty = true;
            appui.fn.closePopup();
          });
          $("a.k-button:last", w).on("click", function(){
            appui.fn.closePopup();
          });
        });
      });
      // Create themes grid
      $("#y99hu8y4ss3a2s5423ld453wmn", cont).kendoGrid({
        dataSource: {
          data: p.data.themes
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
            name: 'destroy',
            test: data.lng.del,
            template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
          }]
        }],
        remove: function(){
          e.model.dirty = true;
        }
      });
      // Insert new theme file
      $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn", cont).on("click", function(){
        appui.fn.popup($("#9342ja823hioasfy3oi").html(), data.lng.add_theme_file, 850, false, function(w){
          $("#845hiay8h9fhuwiey823hi", w).kendoTreeView({
            dataSource: p.data.themes_tree,
            select: function(s){
              $("input[name=path]", w).val(s.sender.dataItem(s.node).path);
            },
            expand: function(){
              setTimeout(function(){
                w.data("kendoWindow").trigger("resize");
              }, 300);
            },
            collapse: function(){
              setTimeout(function(){
                w.data("kendoWindow").trigger("resize");
              }, 300);
            }
          });
          $("a.k-button:first", w).on("click", function(){
            $("#y99hu8y4ss3a2s5423ld453wmn", cont).data("kendoGrid").dataSource.add({path: $("input[name=path]", w).val()});
            e.model.dirty = true;
            appui.fn.closePopup();
          });
          $("a.k-button:last", w).on("click", function(){
            appui.fn.closePopup();
          });
        });
      });
      // Dependecies grid
      $("#732ijfasASdha92389yasdh9823", cont).kendoGrid({
        dataSource: {
          data: p.data.dependencies,
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
            $.each(p.data.lib_ver, function(i,e){
              if ( appui.fn.search(libraries, 'lib_name', e.lib_name) < 0 ){
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
                data: p.data.lib_ver,
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
        remove: function(){
          e.model.dirty = true;
        },
        save: function(){
          e.model.dirty = true;
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

      // Latest
      if ( p.data.latest ){
        $("#hw4o5923noasd890324yho").attr({checked: 'checked', disabled: 'disabled'});
      }
      $("#hw4o5923noasd890324yho").on("click", function(){
        if ( !$(this).attr('checked') ){
          e.model.dirty = true;
        }
      });
      setTimeout(function(){
        kcont.center();
      }, 100);
    }
  });
};