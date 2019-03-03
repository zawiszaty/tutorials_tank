import React from 'react';
import PropTypes from 'prop-types';
import {withStyles} from '@material-ui/core/styles';
import Button from '@material-ui/core/Button';
import Avatar from '@material-ui/core/Avatar';
import List from '@material-ui/core/List';
import ListItem from '@material-ui/core/ListItem';
import ListItemAvatar from '@material-ui/core/ListItemAvatar';
import ListItemText from '@material-ui/core/ListItemText';
import DialogTitle from '@material-ui/core/DialogTitle';
import Dialog from '@material-ui/core/Dialog';
import PersonIcon from '@material-ui/icons/Person';
import AddIcon from '@material-ui/icons/Add';
import Typography from '@material-ui/core/Typography';
import blue from '@material-ui/core/colors/blue';
import {toast} from "react-toastify";
import {withRouter} from "react-router-dom";
import axios from './../../../axios/axios';

const emails = ['username@gmail.com', 'user02@gmail.com'];
const styles = {
    avatar: {
        backgroundColor: blue[100],
        color: blue[600],
    },
    button_wraper: {
        display: "flex",
        justifyContent: "center",
    }
};

class SimpleDialog extends React.Component {
    handleClose = () => {
        this.props.handleClose();
    };


    render() {
        const {classes, onClose, ...other} = this.props;

        return (
            <Dialog onClose={this.handleClose} aria-labelledby="simple-dialog-title" {...other}>
                <DialogTitle id="simple-dialog-title">Czy napewno chcesz usunąć</DialogTitle>
                <div className={classes.button_wraper}>
                    <Button variant="outlined" fullWidth onClick={() => {
                        axios.delete(`/api/v1/post/${this.props.id}`, {
                            headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                        }).then((response) => {
                            toast.success("Pomyślnie usunięto", {
                                position: toast.POSITION.BOTTOM_RIGHT
                            });
                            this.props.history.push('/');
                            this.handleClose();
                        }).catch((e) => {
                            toast.error("Coś poszło nie tak :(", {
                                position: toast.POSITION.BOTTOM_RIGHT
                            });
                            this.props.history.push('/');
                            this.handleClose();
                        })
                    }}>
                        Usuń
                    </Button>
                </div>
            </Dialog>
        );
    }
}

SimpleDialog.propTypes = {
    classes: PropTypes.object.isRequired,
    onClose: PropTypes.func,
};

export default withRouter(withStyles(styles)(SimpleDialog));