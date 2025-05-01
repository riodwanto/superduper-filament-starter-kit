// When the user navigates to the first record, 
// we need to set the browser history
document.addEventListener('recordNavigation.startRecordNavigation', (event) => {
  const { recordId, url } = event.detail[0];
  window.history.replaceState({ recordId }, '', url);
});

// When the user navigates back or forward in the browser, 
// we need to update the record
window.addEventListener("popstate", (event) => {
  if (event.state) {
    Livewire.dispatch('recordNavigation.executeChangeRecord', { 'recordId': event.state.recordId });
  }
});

// When the user navigates to a new record with the filament navigation, 
// we need to update the browser history
document.addEventListener('recordNavigation.changeNavigationRecord', (event) => {
  const { recordId, url, isViewPage, confirmMessage, isDataDirty } = event.detail[0];

  if (!isViewPage) {
    if (isDataDirty) {
      if (confirm(confirmMessage)) {
        window.history.pushState({ recordId }, '', url);
        Livewire.dispatch('recordNavigation.executeChangeRecord', { 'recordId': recordId });
      }
      return;
    }
  }

  window.history.pushState({ recordId }, '', url);
  Livewire.dispatch('recordNavigation.executeChangeRecord', { 'recordId': recordId });
}); 