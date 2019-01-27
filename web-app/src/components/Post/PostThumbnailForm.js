import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Modal from "@material-ui/core/Modal";
import Typography from "@material-ui/core/Typography";
import withStyles from "@material-ui/core/es/styles/withStyles";
import Button from "@material-ui/core/Button";
import {TextValidator, ValidatorForm} from "react-material-ui-form-validator";
import CircularProgress from "@material-ui/core/CircularProgress";
import green from "@material-ui/core/colors/green";
import {toast} from "react-toastify";
import {Paper} from "@material-ui/core";
import ReactCrop from 'react-image-crop';
import 'react-image-crop/dist/ReactCrop.css';
import Avatar from "@material-ui/core/Avatar";

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
        width: "100%",
        backgroundColor: theme.palette.background.paper,
        boxShadow: theme.shadows[5],
        padding: theme.spacing.unit * 4,
        outline: 'none',
        marginTop: theme.spacing.unit,
        margin: "auto",
        display: "flex",
        flexDirection: "column",
        justifyContent: "center",
        alignItems: "center",
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
        width: '30em',
        height: '30em',
        margin: 'auto',
        marginBottom: '2em',
    },
    button: {
        width: "100%"
    }
});

class PostThumbnailForm extends Component {
    constructor(props) {
        super(props);
        this.state = {
            open: false,
            loading: false,
            image: ``,
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
                        <img src={image} className={this.props.classes.avatar}/>
                </div>
                <input
                    accept="image/*"
                    style={{display: 'none'}}
                    id="raised-button-file"
                    multiple
                    type="file"
                    onChange={(files) => {
                        console.log(files.target.files[0]);
                        const avatar = new Blob([files.target.files[0]]);
                        let image = new FormData();
                        image.append("file", avatar, avatar.name);
                        this.props.handleChangeFile(image);
                        var reader = new FileReader();
                        if (files.target.files[0] instanceof  Blob) {
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
                <label htmlFor="raised-button-file" className={classes.button}>
                    <Button component="span" fullWidth>
                        Upload
                    </Button>
                </label>
            </Paper>
        );
    }
}

PostThumbnailForm.propTypes = {};

export default withStyles(styles)(PostThumbnailForm);