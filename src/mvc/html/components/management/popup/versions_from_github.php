<bbn-form class="bbn-full-screen bbn-c"
          action="cdn/data/version/add"
          :data="dataPost"
          ref="form_versions_fromGithub"
          :buttons="buttonsAction"
          @success="success"
          confirm-leave="<?=_("Are you sure you want to go out?")?>"
>
  <div class="bbn-w-100 bbn-padded">
    <bbn-dropdown style="width:300px"
                  ref="listVerisons"
                  placeholder= "<?=_('Select version')?>"
                  :source="versions"
                  v-model="git_id_ver"
                  class="bbn-padded"
    ></bbn-dropdown>
  </div>
</bbn-form>
