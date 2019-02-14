<bbn-form class="bbn-full-screen bbn-c"
          ref="form"
          :buttons="buttonsAction"
          @submit="submit"
          confirm-leave="<?=_("Are you sure you want to go out?")?>"
>
  <div class="bbn-w-100 bbn-padded">
    <bbn-dropdown style="width:300px"
                  ref="listFolders"
                  placeholder= "<?=_('Select folder version')?>"
                  :source="folders"
                  v-model="folderId"
                  class="bbn-padded"
    ></bbn-dropdown>
    <bbn-button @click="deleteFolder"
                icon="fa fa-trash"
                title="<?=_('Delete Folder')?>"
    ></bbn-button>
  </div>
</bbn-form>
