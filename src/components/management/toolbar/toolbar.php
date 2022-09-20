<!-- Libraries toolbar -->
<bbn-toolbar>
  <div class="bbn-flex-width">
    <div class="bbn-block">
      <!-- Add library button -->
      <bbn-button class="bbn-hsmargin"
                  @click="add"
                  icon="nf nf-fa-archive"
                  secondary="nf nf-fa-plus">
        <?=_("Add library")?>
      </bbn-button>

      <!-- GitHub Updates-->
      <bbn-button @click="checkUpdate"
                  icon="nf nf-fa-github"
                  secondary="nf nf-fa-sync_alt"
                  class="bbn-hsmargin"
                  title="<?=_("Check updates from GitHub")?>">
        <?=_("Check GitHub updates")?>
      </bbn-button>
      <!-- TODO incomplete-->
      <bbn-button @click="showUpdate"
                  icon="nf nf-fa-cubes"
                  style="margin-right: 5px"
                  class="bbn-hsmargin"
                  title="<?=_("Show updates")?>"
                  :disabled= "disabledButton">
        <span v-if="totalUpdateList >0" v-text="totalUpdateList"/>
        <?=_('Updates')?>
      </bbn-button>
    </div>
    <div class="bbn-flex-fill bbn-r">
      <i class="nf nf-fa-search"/>
      <bbn-input class="bbn-hmargin"
                 style="min-width: 25vw; max-width: 50vw; width: 300px; text-align: left"
                 placeholder="Search library"
                 v-model="searchNameLibrary"/>
    </div>
  </div>
  <!-- Search library -->
</bbn-toolbar>
