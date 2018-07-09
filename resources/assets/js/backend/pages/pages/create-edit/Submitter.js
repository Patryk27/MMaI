import swal from 'sweetalert';
import Form from '../../../../base/Form';

/**
 * This class is responsible for serializing and submitting the page's form.
 */
export default class Submitter {

    /**
     * @param {jQuery} form
     * @param {MessageBag} messageBag
     * @param {object} sections
     */
    constructor(form, messageBag, sections) {
        this.$messageBag = messageBag;
        this.$sections = sections;

        this.$form = new Form(
            form.data('method'),
            form.data('url'),
        );

        this.$form.onBeforeSubmit(() => {
            this.$handleBeforeSubmit();
        });

        this.$form.onAfterSubmit((success, response) => {
            this.$handleAfterSubmit(success, response);
        });

        this.$form.onFieldError((fieldName, fieldMessage) => {
            this.$messageBag.addError(fieldMessage);
        });

        form.on('click', '[type="submit"]', () => {
            this.$submit();
        });
    }

    /**
     * @private
     */
    $handleBeforeSubmit() {
        // @todo block the submit button

        this.$sections.mediaLibrary.handleBeforeSubmit();

        this.$sections.pageVariants.forEach((section) => {
            section.handleBeforeSubmit();
        });

        this.$messageBag.clear();
    }

    /**
     * @private
     *
     * @param {boolean} success
     * @param {?object} response
     */
    $handleAfterSubmit(success, response) {
        // @todo unblock the submit button

        this.$sections.mediaLibrary.handleAfterSubmit();

        this.$sections.pageVariants.forEach((section) => {
            section.handleAfterSubmit();
        });

        if (success) {
            swal({
                title: 'Success',
                text: 'Page has been saved.',
                icon: 'success',
            }).then(() => {
                window.location.href = response.data.redirectTo;
            });
        } else {
            this.$messageBag.scrollTo();
        }
    }

    /**
     * @private
     */
    $submit() {
        this.$form.submit(
            this.$serialize(),
        );
    }

    /**
     * @private
     *
     * @returns {object}
     */
    $serialize() {
        // Serialize media library
        const mediaLibrary = this.$sections.mediaLibrary.serialize();

        // Serialize page variants
        const pageVariants =
            this.$sections.pageVariants
                .map((section) => section.serialize())
                .filter((serializedSection) => serializedSection !== null);

        return {
            page: {
                type: 'cms',
            },

            mediaLibrary,
            pageVariants,
        };
    }

}

