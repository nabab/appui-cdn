// All libraries grid
var librariesGrid = $("#RRsj983Jfjnv2kasihj234", ele).kendoGrid({
  dataSource: {
    transport: {
      read: function(o){
        if ( data.all_lib ){
          o.success(data.all_lib);
        }
      },
      create: function(o){
        var files = [];
        $("div", "#joisfd8723hifwe78238hds").each(function(i,v){
          files.push($(v).attr('path'));
        });
        o.data.vname = $("#u93248safn328dasuq89yu").val();
        o.data.files = JSON.stringify(files);
        o.data.languages = JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.data());
        o.data.themes = JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.data());
        o.data.dependencies = $("#732ijfasASdha92389yasdh9823").data("kendoMultiSelect").value();
        appui.fn.post('cdn/libraries', o.data, function(d){
          if ( d.data && d.data.length ){
            librariesGrid.data("kendoGrid").dataSource.data(d.data);
            o.success();
          }
          else {
            o.error();
          }
        });
      },
      update: function(o){
        o.data.edit = 1;
        o.data.new_name = $("input[name=new_name]", appui.fn.get_popup()).val();
        o.data.name = $("input[name=name]:hidden", appui.fn.get_popup()).val();
        appui.fn.post('cdn/libraries', o.data, function(d){
          if ( d.data ){
            o.success(d.data);
          }
          else {
            o.error();
          }
        });
      },
      destroy: function(o){
        if ( o.data.name !== undefined ){
          appui.fn.post('cdn/libraries', {name: o.data.name}, function(d){
            if ( d.data.success ){
              o.success();
            }
            else{
              o.error();
            }
          });
        }
      }
    },
    schema: {
      model :{
        id: "name",
        fields: {
          title: { type: "string" },
          name: { type: "string" },
          fname: { type: "string" },
          //latest: { type: "string"},
          website: { type: "string" },
          author: { type: "string" },
          licence: { type: "string" },
          download_link: { type: "string" },
          doc_link: { type: "string" },
          git: { type: "string" },
          support_link: { type: "string" },
          vname: { type: "string" },
          files: { type: "string" },
          languages: { type: "string" },
          themes: { type: "string" },
          dependencies: { type: "string" }
        }
      }
    }
  },
  columns: [{
    title: data.lng.title,
    field: 'title'
  }, {
    title: data.lng.folderName,
    field: 'name',
  }, {
    title: data.lng.functionName,
    field: 'fname'
  }, {
    title: data.lng.latest,
    field: 'latest'
  }, {
    title: data.lng.author,
    field: 'author',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.author && e.author.length ? '<i class="fa fa-user"></i>' : '';
    }
  }, {
    title: data.lng.licence,
    field: 'licence',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.licence && e.licence.length ? '<i class="fa fa-copyright"></i>' : '';
    },
    editor: function(container, options){
      var $input = $('<input name="' + options.field + '" style="width: 100%">');
      $input.appendTo(container).kendoDropDownList({
        dataSource: data.licences,
        dataTextField:  "name",
        dataValueField: "licence",
        optionLabel: data.lng.SelectOne
      });
    }
  }, {
    title: data.lng.webSite,
    field: 'website',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.website && e.website.length ? '<a href="' + e.website + '" target="_blank"><i class="fa fa-globe"></i></a>' : '';
    }
  }, {
    title: data.lng.download,
    field: 'download_link',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.download_link && e.download_link.length ? '<a href="' + e.download_link + '" target="_blank"><i class="fa fa-download"></i></a>' : '';
    }
  }, {
    title: data.lng.doc,
    field: 'doc_link',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.doc_link && e.doc_link.length ? '<a href="' + e.doc_link + '" target="_blank"><i class="fa' +
      ' fa-book"></i></a>' : '';
    }
  }, {
    title: data.lng.gitHub,
    field: 'git',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.git && e.git.length ? '<a href="' + e.git + '" target="_blank"><i class="fa fa-github"></i></a>' : '';
    }
  }, {
    title: data.lng.supp,
    field: 'support_link',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.support_link && e.support_link.length ? '<a href="' + e.support_link + '" target="_blank"><i class="fa' +
      ' fa-ambulance"></i></a>' : '';
    }
  }, {
    title: '',
    width: 110,
    command: [{
      name: "info",
      template: '<a class="k-button k-grid-info fa fa-info" href="javascript:;"></a>'
    }, {
      name: "edit",
      text: {
        edit: data.lng.mod,
        update: data.lng.save,
        cancel: data.lng.cancel
      },
      template: '<a class="k-button k-grid-edit fa fa-edit" href="javascript:;"></a>'
    }, {
      name: 'destroy',
      text: data.lng.del,
      template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'

    }]
  }],
  toolbar: function(){
    return '<div class="toolbar">' +
        // Search library
        '<div style="width: 50%; display: inline-block">' +
          '<i class="fa fa-search" style="margin: 0 5px"></i>' +
          '<input id="F4LLL9jdn3nhasS38sh301dfs" style="width: 300px">' +
        '</div>' +
        // Add library button
        '<div style="width: 50%; display: inline-block; text-align: right"">' +
          '<a class="k-button k-button-icontext k-grid-add" href="javascript:;">' +
            '<i class="fa fa-plus" style="margin-right: 5px"></i>Add library' +
          '</a>' +
        '</div>' +
      '</div>';
  },
  editable: {
    mode: "popup",
    confirmation: data.lng.delete_this_entry,
    window: {
      width: 850,
      maxHeight: appui.env.height - 50
    }
  },
  edit: function(e){
    var cont = e.container,
        kcont = cont.data("kendoWindow");
    // Set title
    kcont.title(
      e.model.name ? data.lng.editLib : data.lng.new_library
    );

    // Fix inputs width
    $("input", cont).width("90%");

    // Set required inputs
    $("input[name=title], input[name=name]", cont).attr("required", "required");

    // Remove "latest" input
    $("input[name=latest]").parent().prev().remove();
    $("input[name=latest]").parent().remove();

    // Insert mode
    if ( !e.model.name ){
      // Add version form
      $("div.k-edit-form-container", cont).prepend($("#932f9u4923rjasdu09j3333").html());
      // Hide "Save" button
      $("a.k-button.k-grid-update", "div.k-edit-buttons", cont).hide();
      // Insert "Next" button
      $("div.k-edit-buttons", cont).append(
        $('<a href="#" class="k-button k-button-icontext b-next">' +
          data.lng.next +
          '<span class="k-icon k-i-arrow-e" style="margin-left: 3px; margin-right: -3px"></span>' +
          '</a>').click(function(){
          if ( $("input[name=name]", cont).val().length ){
            appui.fn.post('cdn/versions', {
              folder: $("input[name=name]", cont).val()
            }, function(d){
              if ( d.data.version && d.data.tree ){
                // Change window title
                kcont.title(data.lng.new_libr_vers);
                // Hide library form
                $("div.k-edit-field:visible, div.k-edit-label:visible", cont).hide();
                // Hide next button
                $("a.k-button.b-next", "div.k-edit-buttons", cont).hide();
                // Show version form
                $("#asdahf8923489yhf98923hr").show();
                // Set version's name
                $("#u93248safn328dasuq89yu").val(d.data.version);
                // Create files treeviews
                $("#ashd3538y1i35h8oasdj023").kendoTreeView({
                  dataSource: d.data.tree,
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
                      kcont.resize();
                      kcont.center();
                    }, 300);
                  },
                  collapse: function(){
                    setTimeout(function(){
                      kcont.resize();
                      kcont.center();
                    }, 300);
                  }
                });
                // Drag&Drop files reorder
                $("#joisfd8723hifwe78238hds").kendoDraggable({
                  filter: 'div.k-alt',
                  group: 'filesGroup',
                  threshold: 100,
                  axis: 'y',
                  hint: function(e){
                    return e.clone().css('background-color', 'green');
                  }
                });
                $("#joisfd8723hifwe78238hds").kendoDropTarget({
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
                $("#y7hhiawza3u9y983w2asj9h9xe4").kendoGrid({
                  dataSource: {
                    data: []
                  },
                  editable: 'popup',
                  columns: [{
                    title: data.lng.files,
                    field: 'path'
                  }, {
                    title: '',
                    width: 40,
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
                $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4").on("click", function(){
                  appui.fn.alert($("#9342ja823hioasfy3oi").html(), data.lng.add_language, 850, false, function(w){
                    // Set dynamic window's height
                    w.closest(".k-window").height("");
                    // Center the window
                    w.data("kendoWindow").center();
                    $("#845hiay8h9fhuwiey823hi").kendoTreeView({
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
                      $("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.add({path: $("input[name=path]", w).val()});
                      appui.fn.closeAlert();
                    });
                    $("a.k-button:last", w).on("click", function(){
                      appui.fn.closeAlert();
                    });
                  });
                });
                // Create themes grid
                $("#y99hu8y4ss3a2s5423ld453wmn").kendoGrid({
                  dataSource: {
                    data: []
                  },
                  editable: 'popup',
                  columns: [{
                    title: data.lng.files,
                    field: 'path'
                  }, {
                    title: '',
                    width: 40,
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
                $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn").on("click", function(){
                  appui.fn.alert($("#9342ja823hioasfy3oi").html(), data.lng.add_theme_file, 850, false, function(w){
                    w.closest(".k-window").height("");
                    w.data("kendoWindow").center();
                    $("#845hiay8h9fhuwiey823hi").kendoTreeView({
                      dataSource: d.data.tree,
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
                      $("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.add({path: $("input[name=path]", w).val()});
                      appui.fn.closeAlert();
                    });
                    $("a.k-button:last", w).on("click", function(){
                      appui.fn.closeAlert();
                    });
                  });
                });
                // Dependences multiselect
                $("#732ijfasASdha92389yasdh9823").kendoMultiSelect({
                  dataSource: {
                    data: d.data.lib_ver,
                    group: {
                      field: "lib"
                    }
                  },
                  dataTextField: "name",
                  dataValueField: "id_ver",
                  placeholder: data.lng.select_dependece
                });
                // Show save button
                $("a.k-button.k-grid-update", "div.k-edit-buttons", cont).show();
                // Add before button
                $("div.k-edit-buttons", cont).prepend(
                  $('<a href="#" class="k-button k-button-icontext b-before">' +
                    '<span class="k-icon k-i-arrow-w"></span>' +
                    data.lng.before +
                    '</a>').click(function(){
                    // Change window title
                    kcont.title(data.lng.new_library);
                    // Show library form inputs
                    $("div.k-edit-field:hidden, div.k-edit-label:hidden", cont).show();
                    // Hide version form
                    $("#asdahf8923489yhf98923hr").hide();
                    // Hide save and before buttons
                    $("a.k-button.k-grid-update, a.k-button.b-before", "div.k-edit-buttons", cont).hide();
                    // Show next button
                    $("a.k-button.b-next", "div.k-edit-buttons", cont).show();
                  })
                );
                cont.redraw();
              }
            });
          }
          else {
            alert(data.lng.allert);
            $("input[name=name]", cont).focus();
          }
        })
      );
    }
    else {
      // Change input name attribute to name field in "new_name"
      $("input[name=name]", cont).attr("name", "new_name");
      // Add a hidden input for stored the name (id)
      $("div.k-edit-form-container", cont).append($('<input type="hidden" name="name">').val(e.model.name));
    }
  },
  // Library's versions subgrid
  detailInit: function(d){
    var versionsGrid = $("<div/>").appendTo(d.detailCell).kendoGrid({
      dataSource: {
        transport: {
          read: function(o){
            appui.fn.post('cdn/versions', {id_lib: d.data.name}, function(p){
              if ( p.data ){
                o.success(p.data);
              }
            });
          },
          update: function(o){
            if ( o.data.id !== undefined ){
              var files = [];
              $("div", "#joisfd8723hifwe78238hds").each(function(i,v){
                files.push($(v).attr('path'));
              });
              appui.fn.post('cdn/versions', {
                id_ver: o.data.id,
                files: JSON.stringify(files),
                languages: JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.data()),
                themes: JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.data()),
                dependencies: $("#732ijfasASdha92389yasdh9823").data("kendoMultiSelect").value(),
                latest: $("#hw4o5923noasd890324yho:checked").length
              }, function(d){
                if ( d.data.success ){
                  versionsGrid.data("kendoGrid").dataSource.read();
                  o.success();
                }
                else {
                  o.error();
                }
              });
            }
          },
          destroy: function(o){
            if ( o.data.id !== undefined ){
              appui.fn.post('cdn/versions', {id_ver: o.data.id}, function(p){
                if ( p.data.success ){
                  o.success();
                }
                else {
                  o.error();
                }
              });
            }
          }
        },
        schema: {
          model :{
            id: "id",
            fields: {
              id: { type: "numer", editable: false },
              name: { type: "string" },
              library: { type: "string" },
              content: { type: "string" },
              date_added: { type: "date" }
            }
          }
        }
      },
      columns: [{
        title: data.lng.version,
        field: 'name'
      }, {
        title: data.lng.date,
        field: 'date_added',
        template: function(e){
          return kendo.toString(e.date_added, "dd/MM/yyyy");
        }
      }, {
        title: '',
        width: 110,
        headerTemplate: '<a href="javascript:;" class="k-button k-button-icontext k-grid-add fa fa-plus add-ver"></a>',
        headerAttributes: {
          style: "text-align: center"
        },
        command: [{
          name: "info",
          template: '<a class="k-button k-grid-d-info fa fa-info" href="javascript:;"></a>'
        }, {
          name: "edit",
          text: {
            edit: data.lng.mod,
            update: data.lng.save,
            cancel: data.lng.cancel,
          },
          template: '<a class="k-button k-grid-edit fa fa-edit" href="javascript:;"></a>'
        }, {
          name: "destroy",
          text: dat.lng.del,
          template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
        }]
      }],
      dataBound: function(db){
        // Library's version info popup
        $("a.k-grid-d-info", versionsGrid).on("click", function(e){
          var item = $(e.target).closest("tr[role=row]"),
            grid = $(item).closest("div.k-grid").data("kendoGrid"),
            dataItem = grid.dataItem(item);

          // Date fix
          dataItem.date_added = kendo.toString(kendo.parseDate(dataItem.date_added, "yyyy-MM-dd HH:mm:ss"), "dd/MM/yyyy");

          appui.fn.alert(
            $("#kkk3jaSdh23490hqAsdha93").html(),
            kendo.format(data.lng.libraryVersion, dataItem.library, dataItem.name),
            800, 800,
            function(w){
              var cont = w,
                kcont = w.data("kendoWindow");

              // Set window's max height
              kcont.setOptions({maxHeight: appui.env.height - 50});

              // Set dynamic window's height
              cont.closest(".k-window").height("");

              // Bind version's info
              kendo.bind(cont, dataItem);
              // Version's files TreeView
              $("#daij444jasdjhi332jiosdajo").html(dataItem.files_tree);
              // Version's dependencies list
              $("#isfih3huasdf92huf823hyhas93").html(function(){
                if ( dataItem.dependencies.length ){
                  var ret = '<ul>';
                  $.each(dataItem.dependencies, function(i,v){
                    ret += '<li>' + v.title + ' - ' + v.version + '</li>';
                  });
                  ret += '</ul>';
                  return ret;
                }
                return '<div style="text-align: center">' + data.lng.no_depend + '</div>';
              });

              // Center window
              kcont.center();
            }
          );
        });
      },
      editable: {
        mode: "popup",
        confirmation: data.lng.delete_this_entry,
        window: {
          title: data.lng,edit_library,
          width: appui.env.width - 100,
          maxHeight: appui.env.height - 150
        }
      },
      edit: function(e){
        var cont = e.container,
            kcont = cont.data("kendoWindow");

        // Remove all field
        $("div.k-edit-field, div.k-edit-label", cont).remove();
        // Insert template
        $("div.k-edit-form-container", cont).prepend($("#932f9u4923rjasdu09j3333").html());
        appui.fn.post('cdn/versions', {version: e.model.id}, function(p){
          if ( p.data ){
            // Show form
            $("#asdahf8923489yhf98923hr").show();
            // Set version's name
            $("#u93248safn328dasuq89yu").val(e.model.name).attr('readonly', 'readonly');
            // Create files treeviews
            $("#ashd3538y1i35h8oasdj023").kendoTreeView({
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
                  kcont.resize();
                  kcont.center();
                }, 300);
              },
              collapse: function(){
                setTimeout(function(){
                  kcont.resize();
                  kcont.center();
                }, 300);
              }
            });
            // Add checked files to files reoder list
            addDelFilesOrder(p.data.files, true);
            // Drag&Drop files reorder
            $("#joisfd8723hifwe78238hds").kendoDraggable({
              filter: 'div.k-alt',
              group: 'filesGroup',
              threshold: 100,
              axis: 'y',
              hint: function(e){
                return e.clone().css('background-color', 'green');
              }
            });
            $("#joisfd8723hifwe78238hds").kendoDropTarget({
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
            $("#y7hhiawza3u9y983w2asj9h9xe4").kendoGrid({
              dataSource: {
                data: p.data.languages
              },
              editable: 'popup',
              columns: [{
                title: data.lng.files,
                field: 'path'
              }, {
                title: '',
                width: 40,
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
            $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4").on("click", function(){
              appui.fn.alert($("#9342ja823hioasfy3oi").html(), data.lng.add_language, 850, false, function(w){
                w.closest(".k-window").height("");
                w.data("kendoWindow").center();
                $("#845hiay8h9fhuwiey823hi").kendoTreeView({
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
                  $("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.add({path: $("input[name=path]", w).val()});
                  e.model.dirty = true;
                  appui.fn.closeAlert();
                });
                $("a.k-button:last", w).on("click", function(){
                  appui.fn.closeAlert();
                });
              });
            });
            // Create themes grid
            $("#y99hu8y4ss3a2s5423ld453wmn").kendoGrid({
              dataSource: {
                data: p.data.themes
              },
              editable: 'popup',
              columns: [{
                title: data.lng.files,
                field: 'path'
              }, {
                title: '',
                width: 40,
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
            $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn").on("click", function(){
              appui.fn.alert($("#9342ja823hioasfy3oi").html(), data.lng.add_theme_file, 850, false, function(w){
                w.closest(".k-window").height("");
                w.data("kendoWindow").center();
                $("#845hiay8h9fhuwiey823hi").kendoTreeView({
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
                  $("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.add({path: $("input[name=path]", w).val()});
                  e.model.dirty = true;
                  appui.fn.closeAlert();
                });
                $("a.k-button:last", w).on("click", function(){
                  appui.fn.closeAlert();
                });
              });
            });
            // Dependences multiselect
            $("#732ijfasASdha92389yasdh9823").kendoMultiSelect({
              dataSource: {
                data: p.data.lib_ver,
                group: {
                  field: "lib"
                }
              },
              dataTextField: "Name",
              dataValueField: "id_ver",
              placeholder: data.lng.select_dependece,
              value: p.data.dependencies,
              change: function(){
                e.model.dirty = true;
              }
            });
            // Add latest checkbox
            $("#asdahf8923489yhf98923hr").append(
              '<div class="appui-form-label appui-r" style="padding: 0; width: 120px">Latest</div>' +
              '<div class="appui-form-field">' +
                '<input id="hw4o5923noasd890324yho" type="checkbox" class="k-checkbox">' +
                '<label for="hw4o5923noasd890324yho" class="k-checkbox-label"></label>' +
              '</div>'
            );
            if ( p.data.latest ){
              $("#hw4o5923noasd890324yho").attr({checked: 'checked', disabled: 'disabled'});
            }
            $("#hw4o5923noasd890324yho").on("click", function(){
              if ( !$(this).attr('checked') ){
                e.model.dirty = true;
              }
            });
          }
          // Window redraw
          $(cont).redraw();
          // Center the window
          kcont.center();
        });
      }
    });
    // Insert new library's version (Plus button)
    $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-ver", d.detailCell).on("click", function(e){
      appui.fn.alert($("#932f9u4923rjasdu09j3333").html(), kendo.format(data.lng.add_version, d.data.title), appui.env.width - 100, false, function(w){
        var cont = w,
            kcont = cont.data("kendoWindow");

        // Set dynamic window's height
        cont.closest(".k-window").height("");
        appui.fn.post('cdn/versions', {folder: d.data.name}, function(p){
          // Show form
          $("#asdahf8923489yhf98923hr").show();
          // Set version's name
          $("#u93248safn328dasuq89yu").val(p.data.version).attr('readonly', 'readonly');
          // Create files treeviews
          $("#ashd3538y1i35h8oasdj023").kendoTreeView({
            dataSource: p.data.tree,
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
                kcont.trigger("resize");
              }, 300);
            },
            collapse: function(){
              setTimeout(function(){
                kcont.trigger("resize");
              }, 300);
            }
          });
          // Drag&Drop files reorder
          $("#joisfd8723hifwe78238hds").kendoDraggable({
            filter: 'div.k-alt',
            group: 'filesGroup',
            threshold: 100,
            axis: 'y',
            hint: function(e){
              return e.clone().css('background-color', 'green');
            }
          });
          $("#joisfd8723hifwe78238hds").kendoDropTarget({
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
          $("#y7hhiawza3u9y983w2asj9h9xe4").kendoGrid({
            dataSource: {
              data: []
            },
            editable: 'popup',
            columns: [{
              title: data.lng.files,
              field: 'path'
            }, {
              title: '',
              width: 40,
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
          $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4").on("click", function(){
            appui.fn.alert($("#9342ja823hioasfy3oi").html(), data.lng.add_language, 850, false, function(a){
              a.closest(".k-window").height("");
              a.data("kendoWindow").center();
              $("#845hiay8h9fhuwiey823hi").kendoTreeView({
                dataSource: p.data.languages_tree,
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
                $("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.add({path: $("input[name=path]", a).val()});
                appui.fn.closeAlert();
              });
              $("a.k-button:last", a).on("click", function(){
                appui.fn.closeAlert();
              });
            });
          });
          // Create themes grid
          $("#y99hu8y4ss3a2s5423ld453wmn").kendoGrid({
            dataSource: {
              data: []
            },
            editable: 'popup',
            columns: [{
              title: data.lng.files,
              field: 'path'
            }, {
              title: '',
              width: 40,
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
          $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn").on("click", function(){
            appui.fn.alert($("#9342ja823hioasfy3oi").html(), data.lng.add_theme_file, 850, false, function(a){
              a.closest(".k-window").height("");
              a.data("kendoWindow").center();
              $("#845hiay8h9fhuwiey823hi").kendoTreeView({
                dataSource: p.data.tree,
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
                $("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.add({path: $("input[name=path]", a).val()});
                appui.fn.closeAlert();
              });
              $("a.k-button:last", a).on("click", function(){
                appui.fn.closeAlert();
              });
            });
          });
          // Dependences multiselect
          $("#732ijfasASdha92389yasdh9823").kendoMultiSelect({
            dataSource: {
              data: p.data.lib_ver,
              group: {
                field: "lib"
              }
            },
            dataTextField: "Name",
            dataValueField: "id_ver",
            placeholder: data.lng.select_dependence,
          });
          // Add save and cancel buttons
          $("#asdahf8923489yhf98923hr").append(
            '<div class="appui-form-label appui-r" style="padding: 0; width: 120px">Latest</div>' +
            '<div class="appui-form-field">' +
              '<input id="hw4o5923noasd890324yho" type="checkbox" class="k-checkbox">' +
              '<label for="hw4o5923noasd890324yho" class="k-checkbox-label"></label>' +
            '</div>' +
            '<div class="appui-form-label" style="padding: 0; width: 120px"></div>' +
            '<div class="appui-form-field" style="text-align: right">' +
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
          $("span.k-update", $("#asdahf8923489yhf98923hr")).parent().on("click", function(){
            var files = [];
            $("div", "#joisfd8723hifwe78238hds").each(function(i,v){
              files.push($(v).attr('path'));
            });
            appui.fn.post('cdn/versions', {
              name: d.data.name,
              vname: p.data.version,
              files: JSON.stringify(files),
              languages: JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.data()),
              themes: JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.data()),
              dependencies: $("#732ijfasASdha92389yasdh9823").data("kendoMultiSelect").value(),
              latest: $("#hw4o5923noasd890324yho:checked").length
            }, function(i){
              if ( i.data.success ){
                $("div.k-grid", d.detailCell).data("kendoGrid").dataSource.read();
                appui.fn.closeAlert();
              }
            });
          });
          $("span.k-cancel", $("#asdahf8923489yhf98923hr")).parent().on("click", function(){
            appui.fn.closeAlert();
          });
          // Call window resize
          kcont.trigger("resize");
        });
      });
    });
  }
}),

// Function for get the treeview checked elements
TVgetChecked = function(treeView){
  var checkedNodes = [],
    getChecked = function(nodes, checkedNodes){
      var getItems = function(nodes){
        for ( var i = 0; i < nodes.length; i++ ){
          if ( (nodes[i].items !== undefined) && nodes[i].items.length ){
            getItems(nodes[i].items);
          }
          else if ( nodes[i].path ){
            checkedNodes.push(nodes[i].path);
          }
        }
      };
      for ( var i = 0; i < nodes.length; i++ ){
        if ( nodes[i].checked && nodes[i].path && !nodes[i].hasChildren ){
          checkedNodes.push(nodes[i].path);
        }
        else if ( nodes[i].checked && nodes[i].hasChildren ){
          getItems(nodes[i].children.options.data.items);
        }
        else if ( !nodes[i].checked && nodes[i].hasChildren ){
          getChecked(nodes[i].children.view(), checkedNodes);
        }
      }
    };
  getChecked(treeView.dataSource.view(), checkedNodes);
  return JSON.stringify(checkedNodes);
},

// Function to add/remove files to/from version's files reorder list
addDelFilesOrder = function(item, forceAdd){
  var kcont = $("#ashd3538y1i35h8oasdj023").closest("div.k-window-content").data("kendoWindow");

  if ( item.length ){
    $.each(item, function(i, v){
      addDelFilesOrder(v, forceAdd);
    });
  }
  else {
    if ( item.items !== undefined ){
      addDelFilesOrder(item.items);
    }
    else {
      if ( item.checked || forceAdd ){
        $("#joisfd8723hifwe78238hds").append(
          '<div path="' + item.path + '" class="k-alt" style="margin-bottom: 5px;">' +
          '<i class="fa fa-arrows" style="margin-right: 5px"></i>' + item.path +
          '</div>'
        );
      }
      else {
        $("div[path='" + item.path + "']", "#joisfd8723hifwe78238hds").remove();
      }
      kcont.setOptions({maxHeight: appui.env.height - 150});
      kcont.resize();
      kcont.center();
    }
  }
};

$("a.k-grid-info", librariesGrid).on("click", function(e){
  appui.fn.log(e);
  var grid = librariesGrid.data("kendoGrid"),
      dataItem = grid.dataItem($(e.target).closest("tr.k-master-row"));
  appui.fn.log(grid, dataItem);

  appui.fn.alert($("#i3h34uefn94uh3rnfe9sfd23u").html(), data.lng.library + ':'  + dataItem.title, 600, false, function(w){
    var cont = w,
        kcont = w.data("kendoWindow");

    // Set window's max height
    kcont.setOptions({maxHeight: appui.env.height - 50});

    // Set dynamic window's height
    cont.closest(".k-window").height("");

    // Bind library's info
    kendo.bind(w, dataItem);
    // Library's versions grid
    appui.fn.post('cdn/versions', {id_lib: dataItem.name}, function(p){
      if ( p.data !== undefined ){
        $("#hufsa93hias9n38fn3293h389r2").kendoGrid({
          dataSource: p.data,
          dataBound: function(){
            kcont.trigger("resize");
          },
          columns: [{
            title: data.lng.name,
            field: 'name'
          }, {
            title: data.lng.date,
            field: 'date_added',
            template: function(t){
              return kendo.toString(kendo.parseDate(t.date_added, "yyyy-MM-dd HH:mm:ss"), "dd/MM/yyyy")
            }
          }]
        });
      }
    });
  });
});



// Search field
appui.fn.log("LANGUAGES", data.lng);
$("#F4LLL9jdn3nhasS38sh301dfs").kendoAutoComplete({
  placeholder: data.lng.search_library +'...',
});