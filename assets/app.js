/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import Like from './scripts/like';
import Comment from './scripts/comment';
import ModalComment from './scripts/modal-comment';
import ModalLike from './scripts/modal-like';
import ShowPosts from './scripts/showPosts';
import ShowRecurrenceCreneau from './scripts/showRecurenceCreneau';

import 'bootstrap';
import ShowImg from './scripts/showImg';
document.addEventListener('DOMContentLoaded', () => {
    const likeElements = [].slice.call(document.querySelectorAll('a[data-action="like"]'));
    const commentElements = [].slice.call(document.querySelectorAll('form[data-action="comment"]'));
    const btnComment = [].slice.call(document.querySelectorAll("#btn-comment"));
    const showLikes = [].slice.call(document.querySelectorAll("#show-likes"));
    const showPosts = [].slice.call(document.querySelectorAll('div[data-hidden="true"]'));
    const images = [].slice.call(document.querySelectorAll('#show-img'));
    const type_creneau = document.querySelector("#creneaux_type");

    if (likeElements) new Like(likeElements);

    if (commentElements) new Comment(commentElements);

    if (btnComment) new ModalComment(btnComment);

    if (showLikes) new ModalLike(showLikes);

    if (showPosts) new ShowPosts(showPosts);

    if (images) new ShowImg(images)

    if(type_creneau) new ShowRecurrenceCreneau(type_creneau);
});