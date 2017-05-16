/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/12/2016
 * Time: 11:55
 */
var libraryAdd = function(e){
  var cont = e.container,
      kcont = cont.data("kendoWindow"),
      showForm = function(info){
        // Remove "Skip" and "Import" buttons
        $("a.k-button.b-skip, a.k-button.b-import", cont).remove();
        // Remove GitHub URL form
        $("form", cont).parent().remove();
        // Add library form template
        $("div.k-edit-form-container", cont).prepend($("#nasdyhi234y89asdnkasdh9854nkasdi").html());
        // Show library form
        $("#lkasu9rnos9ufsdfw9udfsdy8923", cont).show();
        // Add version form template
        $("div.k-edit-form-container", cont).prepend($("#932f9u4923rjasdu09j3333").html());

        if ( info !== undefined ){
          $.each(info, function(i,v){
            if ( e.model[i] !== undefined ){
              e.model.set(i, v);
            }
            else {
              e.model[i] = v;
            }
          });
        }

        kendo.bind($("#lkasu9rnos9ufsdfw9udfsdy8923", cont), e.model);

        bbn.fn.analyzeContent(cont);
        $("div.bbn-form-label", cont).css('padding-right', '0');
        kcont.center();

        // Initialize the dropdown for licence
        $("div#lkasu9rnos9ufsdfw9udfsdy8923 select[name=licence]", cont).kendoDropDownList({
          dataSource: data.licences,
          dataTextField:  "name",
          dataValueField: "licence",
          optionLabel: data.lng.SelectOne
        });

        // Set the latest version on version dropdown
        $("select[name=git_id]", cont).kendoDropDownList({
          dataSource: e.model.versions,
          dataTextField: 'text',
          dataValueField: 'id',
          value: bbn.fn.get_field(e.model.versions, 'is_latest', true, 'id')
        });

        // Insert "Next" button
        $("div.k-edit-buttons", cont).append(
          $('<a href="#" class="k-button k-button-icontext b-next">' +
            data.lng.next +
            '<span class="k-icon k-i-arrow-e" style="margin-left: 3px; margin-right: -3px"></span>' +
            '</a>').click(function(){
            if ( $("input[name=name]", cont).val().length && $("input[name=title]", cont).val().length ){
              bbn.fn.post('cdn/data/version/add', {
                folder: $("input[name=name]", cont).val(),
                git_id_ver: $("select[name=git_id]", cont).data("kendoDropDownList").value(),
                git_user: info !== undefined ? info.user : false,
                git_repo: info !== undefined ? info.repo : false,
                git_latest_ver: info !== undefined ? info.latest : false
              }, function(d){
                if ( d.data && d.data.version && d.data.files_tree ){
                  // Change window title
                  cont.prev().find(".k-window-title:first").html(data.lng.new_libr_vers);
                  // Hide library form
                  $("#lkasu9rnos9ufsdfw9udfsdy8923", cont).hide();
                  // Hide next button
                  $("a.k-button.b-next", "div.k-edit-buttons", cont).hide();
                  // Show version form
                  $("#asdahf8923489yhf98923hr", cont).show();
                  // Bind version form with data
                  kendo.bind($("#asdahf8923489yhf98923hr", cont), d.data);
                  // Reset files treeview
                  if ( $("#ashd3538y1i35h8oasdj023", cont).data("kendoTreeView") !== undefined ){
                    var treeDS = new kendo.data.HierarchicalDataSource({
                      data: d.data.files_tree
                    });
                    $("#ashd3538y1i35h8oasdj023", cont).data("kendoTreeView").setDataSource(treeDS);
                    // Clean files order (drag&drop)
                    $("#joisfd8723hifwe78238hds div.k-alt", cont).remove();
                  }
                  else {
                    // Create files treeviews
                    $("#ashd3538y1i35h8oasdj023", cont).kendoTreeView({
                      dataSource: d.data.files_tree,
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
                          kcont.center();
                        }, 300);
                      },
                      collapse: function(){
                        setTimeout(function(){
                          kcont.center();
                        }, 300);
                      }
                    });
                  }

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
                        name: 'destroy',
                        test: data.lng.del,
                        template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
                      }]
                    }]
                  });
                  // Insert new language file
                  $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4", cont).on("click", function(){
                    bbn.fn.popup($("#9342ja823hioasfy3oi").html(), data.lng.add_language, 850, false, function(w){
                      $("#845hiay8h9fhuwiey823hi", w).kendoTreeView({
                        dataSource: d.data.languages_tree,
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
                        bbn.fn.closePopup();
                      });
                      $("a.k-button:last", w).on("click", function(){
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
                        name: 'destroy',
                        test: data.lng.del,
                        template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
                      }]
                    }]
                  });
                  // Insert new theme file
                  $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn", cont).on("click", function(){
                    bbn.fn.popup($("#9342ja823hioasfy3oi").html(), data.lng.add_theme_file, 850, false, function(w){
                      $("#845hiay8h9fhuwiey823hi", w).kendoTreeView({
                        dataSource: d.data.files_tree,
                        select: function(s){
                          $("input[name=path]", w).val(s.sender.dataItem(s.node).path);
                        },
                        expand: function(){
                          setTimeout(function(){
                            // Window redraw
                            //cont.redraw();
                            // Center the window
                            kcont.center();
                          }, 1000);
                        },
                        collapse: function(){
                          setTimeout(function(){
                            // Window redraw
                            cont.bbn("redraw");
                            // Center the window
                            kcont.center();
                          }, 1000);
                        }
                      });
                      $("a.k-button:first", w).on("click", function(){
                        $("#y99hu8y4ss3a2s5423ld453wmn", cont).data("kendoGrid").dataSource.add({path: $("input[name=path]", w).val()});
                        bbn.fn.closePopup();
                      });
                      $("a.k-button:last", w).on("click", function(){
                        bbn.fn.closePopup();
                      });
                    });
                  });

                  // Remove latest checkboxes
                  $("#hw4o5923noasd890324yho", cont).closest("div.bbn-form-field").prev().remove();
                  $("#hw4o5923noasd890324yho", cont).closest("div.bbn-form-field").remove();

                  // Dependecies grid
                  $("#732ijfasASdha92389yasdh9823", cont).kendoGrid({
                    dataSource: {
                      data: d.data.dependencies,
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
                        $.each(d.data.lib_ver, function(i,e){
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
                            data: d.data.lib_ver,
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

                  // Show save button
                  $("a.k-button.k-grid-update", "div.k-edit-buttons", cont).show();
                  // Add before button
                  if ( !$("a.k-button.b-before", cont).length ){
                    $("div.k-edit-buttons", cont).prepend(
                      $('<a href="#" class="k-button k-button-icontext b-before">' +
                        '<span class="k-icon k-i-arrow-w"></span>' +
                        data.lng.before +
                        '</a>').click(function(){
                        // Change window title
                        cont.prev().find(".k-window-title:first").html(data.lng.new_library);
                        // Show library form
                        $("#lkasu9rnos9ufsdfw9udfsdy8923", cont).show();
                        // Hide version form
                        $("#asdahf8923489yhf98923hr", cont).hide();
                        // Hide save and before buttons
                        $("a.k-button.k-grid-update, a.k-button.b-before", "div.k-edit-buttons", cont).hide();
                        // Show next button
                        $("a.k-button.b-next", "div.k-edit-buttons", cont).show();
                      })
                    );
                  }
                  else {
                    $("a.k-button.b-before", cont).show();
                  }
                }
              });
            }
            else {
              bbn.fn.alert(data.lng.setFolderName);
              $("input[name=name]", cont).focus();
            }
          })
        );
      };

  // Hide "Save" button
  $("a.k-button.k-grid-update", "div.k-edit-buttons", cont).hide();

  // Insert the "Skip" button
  $("div.k-edit-buttons", cont).append(
    $('<a href="#" class="k-button k-button-icontext b-skip">' +
        data.lng.skip +
        '<span class="k-icon k-i-seek-e" style="margin-left: 3px; margin-right: -3px"></span>' +
      '</a>').click(function(){
      showForm();
    })
  );

  // Insert the "Import" button
  $("div.k-edit-buttons", cont).append(
    $('<a href="#" class="k-button k-button-icontext b-import">' +
      data.lng.import +
      '<span class="k-icon k-i-arrow-e" style="margin-left: 3px; margin-right: -3px"></span>' +
      '</a>').click(function(e){
        var url = $("input[name=github]", cont).val();

        if ( url.length && /^(http|https):\/\/[^ "]+$/.test(url) ){
          bbn.fn.post('cdn/github/info', {url: url}, function(d){
            if ( d.data ){
              showForm(d.data);
            }
          });
        }
    })
  );
};