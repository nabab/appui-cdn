
<bbn-form class="bbn-full-screen"
          :action="cdn/actions/library/edit"
          :source="$data"
          ref="form_edit_library"
          confirm-leave="xxxx"
>
 <div class="bbn-padded bbn-grid-fields">
    <label class="bbn-r">
      <?=_("Title")?>
    </label>
    <bbn-input name="title" v-model="title"></bbn-input>

    <label class="bbn-r">
      <?=_("Folder name")?>
    </label>
    <bbn-input v-model="name"></bbn-input>

    <label class="bbn-r">
      <?=_("Function name")?>
    </label>
    <input v-model="fname"></bbn-input>

    <label class="bbn-r">
      <?=_("Description")?>
    </label>
    <bbn-input v-model="description"></bbn-input>

    <label class="bbn-r">
      <?=_("Author")?>
    </label>
    <bbn-input v-model="author"></bbn-input>

    <label class="bbn-r">
      <?=_("Licence")?>
    </label>
    <bbn-dropdown
      style="width:200px"
      ref="listLicences"
      placeholder= "<?=_('Select one')?>"
      :source="licencesList"
      v-model="licence"
    ></bbn-dropdown>

    <label>
      <?=_("Web site")?>
    </label>
    <bbn-input v-model="website"></bbn-input>

    <label class="bbn-r">
      <?=_("Download")?>
    </label>
    <bbn-input v-model="download_link"></bbn-input>

    <label class="bbn-r">
      <?=_("Documentation")?>
    </label>
    <bbn-input v-model="doc_link"></bbn-input>

<!--
    <label><?=_("GitHub")?></label>

      <bbn-input v-model="git"></bbn-input>
      <a class="k-button fa fa-download" title="<?=("Import library's info from GitHub")?>" data-bind="visible: git"></a>
    -->

    <label class="bbn-r">
      <?=_("Support")?>
    </label>
    <bbn-input v-model="support_link"></bbn-input>


    <!--label><?=_("Version")?></label>
    <div  data-bind="visible: versions">
      <select name="git_id" style="width: 50%"></select>
    </div-->
  </div>
</bbn-form>
