<!-- Slave version's dependencies (add mode) -->
<script type="text/html" id="ioasd8923hiasdu9021jio3rhwfe8998a">
  <form action="cdn/data/version/add">
    <input type="hidden" name="git_user" data-bind="value: git_user" required>
    <input type="hidden" name="git_repo" data-bind="value: git_repo" required>
    <input type="hidden" name="git_latest_ver" data-bind="value: latest" required>
    <input type="hidden" name="folder" data-bind="value: folder" required>
    <div class="bbn-form-label bbn-r fix-width no-padding"><?=_("Version")?></div>
    <div class="bbn-form-field">
      <select name="git_id_ver" style="width: 100%" required>
      </select>
    </div>
    <div class="bbn-form-label bbn-r fix-width no-padding"></div>
    <div class="bbn-form-field bbn-r">
      <button class="k-button" type="submit"><i class="fa fa-download"></i> <?=_("Import")?></button>
      <button class="k-button" type="button" onclick="bbn.fn.closePopup();"><i class="fas fa-times"></i> <?=_("Cancel")?></button>
    </div>
  </form>
</script>