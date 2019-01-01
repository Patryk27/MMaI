import { Form } from '../../../../../components/Form';
import { EventBus } from '../../../../../utils/EventBus';

export class PageSection {

    private form: Form;

    constructor(bus: EventBus, container: JQuery) {
        // this.form = new FormComponent({
        //     form: container.find('form'),
        //
        //     components: {
        //         id: new InputComponent(container.find('[name="id"]')),
        //         type: new InputComponent(container.find('[name="type"]')),
        //
        //         url: new InputComponent(container.find('[name="url"]')),
        //         title: new InputComponent(container.find('[name="title"]')),
        //         lead: new InputComponent(container.find('[name="lead"]')),
        //         content: new InputComponent(container.find('[name="content"]')),
        //
        //         tags: new SelectComponent(container.find('[name="tag_ids"]')),
        //         status: new SelectComponent(container.find('[name="status"]')),
        //         website: new SelectComponent(container.find('[name="website_id"]')),
        //     },
        // });
        //
        // this.form.on('change', () => {
        //     bus.emit('form::changed');
        // });
        //
        // this.form.on('keypress', (evt) => {
        //     if (evt.originalEvent.code === 'Enter') {
        //         bus.emit('form::submit');
        //     }
        // });
        //
        // this.form.website.on('change', () => {
        //     // noinspection JSIgnoredPromiseFromCall
        //     this.$refreshTags();
        // });
        //
        // Initialize SimpleMDE
        // this.$simpleMde = new SimpleMDE({
        //     autoDownloadFontAwesome: false,
        //     element: this.$dom.form.find('[name="content"]')[0],
        //     forceSync: true,
        //     spellChecker: false,
        // });
        //
        // When user changes a tab, we may have to refresh ourselves, since SimpleMDE - after becoming visible - tends
        // to forget that it should repaint
        // bus.on('tabs::changed', ({ currentSection }) => {
        //     if (currentSection === 'page') {
        //         this.$focus();
        //     }
        // });
        //
        // this.$focus();
        //
        // // noinspection JSIgnoredPromiseFromCall
        // this.$refreshTags();
    }

    public serialize(): object {
        return {};
        // return this.$form.serialize();
    }

    private focus(): void {
        // this.$form.title.focus();
        // this.$simpleMde.codemirror.refresh();
    }

    private async refreshTags(): Promise<void> {
        // const tagsComponent = this.$form.getComponent('tags');
        // tagsComponent.disable();
        //
        // let tags = await TagsFacade.search({
        //     filters: {
        //         websiteId: this.$form.website.getValue(),
        //     },
        //
        //     orderBy: {
        //         name: 'asc',
        //     },
        // });
        //
        // tags = tags.map((tag) => {
        //     return {
        //         id: tag.id,
        //         label: tag.name,
        //     };
        // });
        //
        // tagsComponent.setOptions(tags);
        // tagsComponent.enable();
    }

}
