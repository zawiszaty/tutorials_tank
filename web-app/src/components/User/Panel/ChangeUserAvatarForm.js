import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Modal from "@material-ui/core/Modal";
import Typography from "@material-ui/core/Typography";
import withStyles from "@material-ui/core/es/styles/withStyles";
import Button from "@material-ui/core/Button";
import {TextValidator, ValidatorForm} from "react-material-ui-form-validator";
import CircularProgress from "@material-ui/core/CircularProgress";
import green from "@material-ui/core/colors/green";
import axios from "../../../axios/axios";
import {toast} from "react-toastify";
import {Paper} from "@material-ui/core";
import ReactCrop from 'react-image-crop';
import 'react-image-crop/dist/ReactCrop.css';
import Avatar from "@material-ui/core/Avatar";
import {ErrorMessage} from "../../Notification/ErrorMessage";

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
        width: theme.spacing.unit * 50,
        backgroundColor: theme.palette.background.paper,
        boxShadow: theme.shadows[5],
        padding: theme.spacing.unit * 4,
        outline: 'none',
        marginTop: theme.spacing.unit
    },
    wrapper: {
        margin: theme.spacing.unit,
        position: 'relative',
        display: 'flex',
        alignItems: 'center',
    },
    buttonSuccess: {
        backgroundColor: green[500],
        '&:hover': {
            backgroundColor: green[700],
        },
    },
    buttonProgress: {
        color: green[500],
        position: 'absolute',
        top: '50%',
        left: '50%',
        marginTop: -12,
        marginLeft: -12,
    },
    avatar: {
        width: '10em',
        height: '10em',
        margin: 'auto',
        marginBottom: '2em',
    }
});

class ChangeUserAvatarForm extends Component {
    constructor(props) {
        super(props);
        this.state = {
            open: false,
            loading: false,
            image: `${this.props.user.avatar}`,
            uploadedImage: '',
            crop: {
                aspect: 1,
                width: 50,
                x: 0,
                y: 0,
            },
        }
    }

    handleOpen = () => {
        this.setState({
            open: true,
        });
    };

    handleClose = () => {
        this.setState({
            open: false,
        });
    };

    handleImage = (image) => {
        this.setState({
            uploadedImage: image
        });
    };

    onImageLoaded = (image, pixelCrop) => {
        this.imageRef = image;
    };

    onCropChange = crop => {
        this.setState({crop});
    };

    async makeClientCrop(crop, pixelCrop) {
        if (this.imageRef && crop.width && crop.height) {
            const croppedImageUrl = await this.getCroppedImg(
                this.imageRef,
                pixelCrop,
                'newFile.jpeg',
            );
            this.setState({
                image: croppedImageUrl,
            });
        }
    }

    getCroppedImg(image, pixelCrop, fileName) {
        const canvas = document.createElement('canvas');
        canvas.width = pixelCrop.width;
        canvas.height = pixelCrop.height;
        const ctx = canvas.getContext('2d');

        ctx.drawImage(
            image,
            pixelCrop.x,
            pixelCrop.y,
            pixelCrop.width,
            pixelCrop.height,
            0,
            0,
            pixelCrop.width,
            pixelCrop.height,
        );

        return new Promise((resolve, reject) => {
            canvas.toBlob(blob => {
                blob.name = fileName;
                window.URL.revokeObjectURL(this.fileUrl);
                this.fileUrl = window.URL.createObjectURL(blob);
                resolve(this.fileUrl);
            }, 'image/jpeg');
        });
    }

    onCropComplete = (crop, pixelCrop) => {
        this.makeClientCrop(crop, pixelCrop);
    };

    render() {
        const {classes, category} = this.props;
        const {image, crop, uploadedImage} = this.state;
        return (
            <Paper className={classes.paper}>
                <div>
                    <Avatar className={classes.avatar}>
                        <img src={this.state.image} className={this.props.classes.avatar}/>
                    </Avatar>
                </div>
                <form name="changeAvatarForm" className={this.props.classes.form} onSubmit={(e) => {
                    e.preventDefault();

                    if (this.state.uploadedImage === '') {
                        toast.info("To ten sam avatar", {
                            position: toast.POSITION.BOTTOM_RIGHT
                        });
                    } else {
                        fetch(this.state.image).then((r) => {
                            let file = r.blob().then((e) => {
                                let image = new FormData();
                                image.append("file", e, e.name);
                                axios.post('/api/v1/user/change/avatar', image, {
                                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                })
                                    .then((response) => {
                                        this.state.image = response.data.avatar;
                                        this.setState({
                                            image: `${response.data.avatar}`
                                        });
                                        toast.success("Awatar zmieniony", {
                                            position: toast.POSITION.BOTTOM_RIGHT
                                        });
                                    })
                                    .catch((e) => {
                                        ErrorMessage(e);
                                    })
                            });
                        });
                    }
                }}>
                    <input
                        accept="image/*"
                        style={{display: 'none'}}
                        id="raised-button-file"
                        multiple
                        type="file"
                        onChange={(files) => {
                            if (files.target.files[0] instanceof Blob) {
                                const avatar = new Blob([files.target.files[0]]);
                                let image = new FormData();
                                image.append("file", avatar, avatar.name);
                                this.setState({
                                    image: image
                                });
                                var reader = new FileReader();
                                reader.readAsDataURL(files.target.files[0]);
                                reader.onload = (e) => {
                                    console.log(e);
                                    this.handleImage(e.target.result);
                                }
                            }
                        }}
                    />
                    {uploadedImage && (
                        <ReactCrop
                            src={uploadedImage}
                            crop={crop}
                            onImageLoaded={this.onImageLoaded}
                            onComplete={this.onCropComplete}
                            onChange={this.onCropChange}
                        />
                    )}
                    <label htmlFor="raised-button-file">
                        <Button component="span" fullWidth>
                            Upload
                        </Button>
                    </label>
                    <Button
                        type="submit"
                        fullWidth
                        color="primary"
                    >
                        Zmień Avatar
                    </Button>
                </form>
            </Paper>
        );
    }
}

ChangeUserAvatarForm.propTypes = {};

export default withStyles(styles)(ChangeUserAvatarForm);