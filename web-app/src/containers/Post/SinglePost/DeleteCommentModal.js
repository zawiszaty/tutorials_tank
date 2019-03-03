import React from 'react';
import PropTypes from 'prop-types';
import {withStyles} from '@material-ui/core/styles';
import Typography from '@material-ui/core/Typography';
import Modal from '@material-ui/core/Modal';
import Button from '@material-ui/core/Button';
import axios from './../../../axios/axios';
import {toast} from "react-toastify";

function rand() {
    return Math.round(Math.random() * 20) - 10;
}

function getModalStyle() {
    const top = 50;
    const left = 50;

    return {
        top: `${top}%`,
        left: `${left}%`,
        transform: `translate(-${top}%, -${left}%)`,
    };
}

const styles = theme => ({
    paper: {
        position: 'absolute',
        width: theme.spacing.unit * 50,
        backgroundColor: theme.palette.background.paper,
        boxShadow: theme.shadows[5],
        padding: theme.spacing.unit * 4,
        outline: 'none',
    },
});

class SimpleModal extends React.Component {
    state = {
        open: false,
    };

    handleOpen = () => {
        this.setState({open: true});
    };

    handleClose = () => {
        this.setState({open: false});
    };

    handleDelete = () => {
        axios.delete(`/api/v1/comment/${this.props.id}`, {
            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
        }).then((response) => {
            toast.success("usunieto", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.setState({open: false});
            this.props.getAllComments();
        }).catch((e) => {
            toast.error("Coś poszło nie tak", {
                position: toast.POSITION.BOTTOM_RIGHT
            });
            this.setState({open: false});
        })
    };

    render() {
        const {classes} = this.props;

        return (
            <div>
                <Button onClick={this.handleOpen} variant="contained"
                        color="secondary"
                        fullWidth
                        type="submit">usuń</Button>
                <Modal
                    aria-labelledby="simple-modal-title"
                    aria-describedby="simple-modal-description"
                    open={this.state.open}
                    onClose={this.handleClose}
                >
                    <div style={getModalStyle()} className={classes.paper}>
                        <Typography variant="h6" id="modal-title">
                            Czy napewno chcesz usunać ?
                        </Typography>
                        <Button onClick={this.handleDelete} variant="contained"
                                color="secondary"
                                type="submit" fullWidth>Tak usuń</Button>
                    </div>
                </Modal>
            </div>
        );
    }
}

SimpleModal.propTypes = {
    classes: PropTypes.object.isRequired,
};

// We need an intermediary variable for handling the recursive nesting.
const DeleteCommentModal = withStyles(styles)(SimpleModal);

export default DeleteCommentModal;