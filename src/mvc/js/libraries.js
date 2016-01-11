// All libraries grid
var librariesGrid = $("#RRsj983Jfjnv2kasihj234").kendoGrid({
  dataSource: {
    transport: {
      read: function(o){
        if ( data.all_lib ){
          o.success(data.all_lib);
        }
      },
      create: function(o){
        o.data.vname = $("#u93248safn328dasuq89yu").val();
        o.data.files = TVgetChecked($("#ashd3538y1i35h8oasdj023").data("kendoTreeView"));
        o.data.languages = JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.data());
        o.data.themes = JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.data());
        o.data.dependencies = $("#732ijfasASdha92389yasdh9823").data("kendoMultiSelect").value();
        appui.f.post('cdn/libraries', o.data, function(d){
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
        o.data.new_name = $("input[name=new_name]", appui.f.get_popup()).val();
        o.data.name = $("input[name=name]:hidden", appui.f.get_popup()).val();
        appui.f.post('cdn/libraries', o.data, function(d){
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
          appui.f.post('cdn/libraries', {name: o.data.name}, function(d){
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
    title: 'Title',
    field: 'title'
  }, {
    title: 'Folder name',
    field: 'name',
  }, {
    title: 'Function name',
    field: 'fname'
  }, {
    title: 'Latest',
    field: 'latest'
  }, {
    title: 'WebSite',
    field: 'website',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.website && e.website.length ? '<a href="' + e.website + '" target="_blank"><i class="fa fa-globe"></i></a>' : '';
    }
  }, {
    title: 'Author',
    field: 'author',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.author && e.author.length ? '<i class="fa fa-user"></i>' : '';
    }
  }, {
    title: 'Licence',
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
        dataTextField: "name",
        dataValueField: "licence",
        optionLabel: "Select one..."
      });
    }
  }, {
    title: 'Download',
    field: 'download_link',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.download_link && e.download_link.length ? '<a href="' + e.download_link + '" target="_blank"><i class="fa fa-download"></i></a>' : '';
    }
  }, {
    title: 'Doc.',
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
    title: 'GitHub',
    field: 'git',
    width: 70,
    attributes:{
      style: "text-align: center"
    },
    template: function(e){
      return e.git && e.git.length ? '<a href="' + e.git + '" target="_blank"><i class="fa fa-github"></i></a>' : '';
    }
  }, {
    title: 'Supp.',
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
    width: 80,
    command: [{
      name: "edit",
      text: {
        edit: "Mod.",
        update: "Save",
        cancel: "Cancel"
      },
      template: '<a class="k-button k-grid-edit fa fa-edit" href="javascript:;"></a>'
    }, {
      name: "destroy",
      text: "Del.",
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
    confirmation: "Are you sure that you want to delete this entry?",
    window: {
      width: 850,
      maxHeight: appui.v.height - 50
    }
  },
  edit: function(e){
    var cont = e.container,
        kcont = cont.data("kendoWindow");

    // Set title
    kcont.title(
      e.model.name ? "Edit Library" : "New Library"
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
          'Next' +
          '<span class="k-icon k-i-arrow-e" style="margin-left: 3px; margin-right: -3px"></span>' +
          '</a>').click(function(){
          if ( $("input[name=name]", cont).val().length ){
            appui.f.post('cdn/versions', {
              folder: $("input[name=name]", cont).val()
            }, function(d){
              if ( d.data.version && d.data.tree ){
                // Change window title
                kcont.title("New Library's Version");
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
                // Create languages grid
                $("#y7hhiawza3u9y983w2asj9h9xe4").kendoGrid({
                  dataSource: {
                    data: []
                  },
                  editable: 'popup',
                  columns: [{
                    title: 'Files',
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
                      test: "Del.",
                      template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
                    }]
                  }]
                });
                // Insert new language file
                $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4").on("click", function(){
                  appui.f.alert($("#9342ja823hioasfy3oi").html(), 'Add language file', 850, false, function(w){
                    w.closest(".k-window").height("");
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
                      appui.f.closeAlert();
                    });
                    $("a.k-button:last", w).on("click", function(){
                      appui.f.closeAlert();
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
                    title: 'Files',
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
                      test: "Del.",
                      template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
                    }]
                  }]
                });
                // Insert new theme file
                $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn").on("click", function(){
                  appui.f.alert($("#9342ja823hioasfy3oi").html(), 'Add theme file', 850, false, function(w){
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
                      appui.f.closeAlert();
                    });
                    $("a.k-button:last", w).on("click", function(){
                      appui.f.closeAlert();
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
                  placeholder: "Select dependences..."
                });
                // Show save button
                $("a.k-button.k-grid-update", "div.k-edit-buttons", cont).show();
                // Add before button
                $("div.k-edit-buttons", cont).prepend(
                  $('<a href="#" class="k-button k-button-icontext b-before">' +
                    '<span class="k-icon k-i-arrow-w"></span>' +
                    'Before' +
                    '</a>').click(function(){
                    // Change window title
                    kcont.title("New Library");
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
              }
            });
          }
          else {
            alert('Set the folder name first, please.');
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
            appui.f.post('cdn/versions', {id_lib: d.data.name}, function(p){
              if ( p.data ){
                o.success(p.data);
              }
            });
          },
          update: function(o){
            appui.f.log(o);
            if ( o.data.id !== undefined ){
              appui.f.post('cdn/versions', {
                id_ver: o.data.id,
                files: TVgetChecked($("#ashd3538y1i35h8oasdj023").data("kendoTreeView")),
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
              appui.f.post('cdn/versions', {id_ver: o.data.id}, function(p){
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
        title: 'Version',
        field: 'name'
      }, {
        title: 'Date',
        field: 'date_added',
        template: function(e){
          return kendo.toString(e.date_added, "dd/MM/yyyy");
        }
      }, {
        title: '',
        width: 80,
        headerTemplate: '<a href="javascript:;" class="k-button k-button-icontext k-grid-add fa fa-plus add-ver"></a>',
        headerAttributes: {
          style: "text-align: center"
        },
        command: [{
          name: "edit",
          text: {
            edit: "Mod.",
            update: "Save",
            cancel: "Cancel"
          },
          template: '<a class="k-button k-grid-edit fa fa-edit" href="javascript:;"></a>'
        }, {
          name: "destroy",
          test: "Del.",
          template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
        }]
      }],
      selectable: true,
      change: function(e){
        var grid = this,
          dataItem = grid.dataItem(this.select());

        appui.f.alert($("#kkk3jaSdh23490hqAsdha93").html(), 'Library: ' + d.data.title + ' - Version: ' + dataItem.name, 1000, 800, function(w){
          // Details tabstrip
          $("#888Hdfh3iaJAsi12KAsdh32lJa").kendoTabStrip({
            animation:  {
              open: {
                effects: "fadeIn"
              }
            }
          });

          // Library tab
          // Load library's info template
          $("#nnnDfgsj939uqkok2912en99asd").html($("#sdg39t3023j9fass8y34t8y8asf3y89").html());
          // Set the library's info inputs to readonly
          $("#nnnDfgsj939uqkok2912en99asd").find("input").each(function(i,v){
            $(v).attr("readonly", "readonly");
          });
          // Bind library's info
          kendo.bind($("#nnnDfgsj939uqkok2912en99asd"), d.data);
          // Library's versions grid
          $("#hufsa93hias9n38fn3293h389r2").kendoGrid({
            dataSource: grid.dataSource.data(),
            columns: [{
              title: 'Name',
              field: 'name'
            }, {
              title: 'Date',
              field: 'date_added',
              template: function(t){
                return kendo.toString(t.date_added, "dd/MM/yyyy")
              }
            }]
          });

          // Version's tab
          // Bind version's info
          kendo.bind($("#i435osojdi2oajsdioasdi22jiod"), dataItem);
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
            return '<div style="text-align: center">NO DEPENDENCIES</div>';
          });

          // TabStrip height fix
          $("div.k-tabstrip-wrapper", w).addClass("appui-full-height");
          $(w).redraw();

        });
      },
      editable: {
        mode: "popup",
        confirmation: "Are you sure that you want to delete this entry?",
        window: {
          title: "Edit library's version",
          width: 850
        }
      },
      edit: function(e){
        var cont = e.container,
            kcont = cont.data("kendoWindow");
        // Set window's max height
        kcont.setOptions({maxHeight: appui.v.height - 50});
        // Remove all field
        $("div.k-edit-field, div.k-edit-label", cont).remove();
        // Insert template
        $("div.k-edit-form-container", cont).prepend($("#932f9u4923rjasdu09j3333").html());
        appui.f.post('cdn/versions', {version: e.model.id}, function(p){
          if ( p.data ){
            // Show form
            $("#asdahf8923489yhf98923hr").show();
            // Set version's name
            $("#u93248safn328dasuq89yu").val(e.model.name).attr('readonly', 'readonly');
            // Create files treeviews
            $("#ashd3538y1i35h8oasdj023").kendoTreeView({
              dataSource: p.data.files,
              checkboxes: {
                checkChildren: true
              },
              check: function(){
                e.model.dirty = true;
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
            // Create languages grid
            $("#y7hhiawza3u9y983w2asj9h9xe4").kendoGrid({
              dataSource: {
                data: p.data.languages
              },
              editable: 'popup',
              columns: [{
                title: 'Files',
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
                  test: "Del.",
                  template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
                }]
              }],
              remove: function(){
                e.model.dirty = true;
              }
            });
            // Insert new language file
            $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4").on("click", function(){
              appui.f.alert($("#9342ja823hioasfy3oi").html(), 'Add language file', 850, false, function(w){
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
                  appui.f.closeAlert();
                });
                $("a.k-button:last", w).on("click", function(){
                  appui.f.closeAlert();
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
                title: 'Files',
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
                  test: "Del.",
                  template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
                }]
              }],
              remove: function(){
                e.model.dirty = true;
              }
            });
            // Insert new theme file
            $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn").on("click", function(){
              appui.f.alert($("#9342ja823hioasfy3oi").html(), 'Add theme file', 850, false, function(w){
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
                  appui.f.closeAlert();
                });
                $("a.k-button:last", w).on("click", function(){
                  appui.f.closeAlert();
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
              dataTextField: "name",
              dataValueField: "id_ver",
              placeholder: "Select dependences...",
              value: p.data.dependencies,
              change: function(){
                e.model.dirty = true;
              }
            });
            // Add latest checkbox
            $("#asdahf8923489yhf98923hr").append(
              '<div class="k-edit-label">Latest</div>' +
              '<div class="k-edit-field">' +
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
          kcont.center();
        });
      }
    });
    // Insert new library's version (Plus button)
    $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-ver", d.detailCell).on("click", function(e){
      appui.f.alert($("#932f9u4923rjasdu09j3333").html(), 'Add ' + d.data.title + ' library\'s version', 850, false, function(w){
        var cont = w,
            kcont = cont.data("kendoWindow");
        // Set window's max height
        kcont.setOptions({maxHeight: appui.v.height - 50});
        // Set dynamic window's height
        cont.closest(".k-window").height("");
        appui.f.post('cdn/versions', {folder: d.data.name}, function(p){
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
          // Create languages grid
          $("#y7hhiawza3u9y983w2asj9h9xe4").kendoGrid({
            dataSource: {
              data: []
            },
            editable: 'popup',
            columns: [{
              title: 'Files',
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
                test: "Del.",
                template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
              }]
            }]
          });
          // Insert new language file
          $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-lang", "#y7hhiawza3u9y983w2asj9h9xe4").on("click", function(){
            appui.f.alert($("#9342ja823hioasfy3oi").html(), 'Add language file', 850, false, function(a){
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
                appui.f.closeAlert();
              });
              $("a.k-button:last", a).on("click", function(){
                appui.f.closeAlert();
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
              title: 'Files',
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
                test: "Del.",
                template: '<a class="k-button k-grid-delete fa fa-trash" href="javascript:;"></a>'
              }]
            }]
          });
          // Insert new theme file
          $("a.k-button.k-button-icontext.k-grid-add.fa.fa-plus.add-theme", "#y99hu8y4ss3a2s5423ld453wmn").on("click", function(){
            appui.f.alert($("#9342ja823hioasfy3oi").html(), 'Add theme file', 850, false, function(a){
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
                appui.f.closeAlert();
              });
              $("a.k-button:last", a).on("click", function(){
                appui.f.closeAlert();
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
            dataTextField: "name",
            dataValueField: "id_ver",
            placeholder: "Select dependences..."
          });
          // Add save and cancel buttons
          $("#asdahf8923489yhf98923hr").append(
            '<div class="k-edit-label">Latest</div>' +
            '<div class="k-edit-field">' +
              '<input id="hw4o5923noasd890324yho" type="checkbox" class="k-checkbox">' +
              '<label for="hw4o5923noasd890324yho" class="k-checkbox-label"></label>' +
            '</div>' +
            '<div class="k-edit-label"></div>' +
            '<div class="k-edit-field" style="text-align: right">' +
              '<a href="#" class="k-button k-button-icontext" style="margin-right: 5px">' +
                '<span class="k-icon k-update"></span>' +
                'Save' +
              '</a>' +
              '<a href="#" class="k-button k-button-icontext">' +
                '<span class="k-icon k-cancel"></span>' +
                'Cancel' +
              '</a>' +
            '</div>'
          );
          $("span.k-update", $("#asdahf8923489yhf98923hr")).parent().on("click", function(){
            appui.f.post('cdn/versions', {
              name: d.data.name,
              vname: p.data.version,
              files: TVgetChecked($("#ashd3538y1i35h8oasdj023").data("kendoTreeView")),
              languages: JSON.stringify($("#y7hhiawza3u9y983w2asj9h9xe4").data("kendoGrid").dataSource.data()),
              themes: JSON.stringify($("#y99hu8y4ss3a2s5423ld453wmn").data("kendoGrid").dataSource.data()),
              dependencies: $("#732ijfasASdha92389yasdh9823").data("kendoMultiSelect").value(),
              latest: $("#hw4o5923noasd890324yho:checked").length
            }, function(i){
              if ( i.data.success ){
                $("div.k-grid", d.detailCell).data("kendoGrid").dataSource.read();
                appui.f.closeAlert();
              }
            });
          });
          $("span.k-cancel", $("#asdahf8923489yhf98923hr")).parent().on("click", function(){
            appui.f.closeAlert();
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
};

// Search field
$("#F4LLL9jdn3nhasS38sh301dfs").kendoAutoComplete({
  placeholder: 'Search library...'
});